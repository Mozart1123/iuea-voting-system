<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Export Controller
 * Handles exporting data in various formats
 */
class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    /**
     * Export election results as CSV
     *
     * @param Category $category
     * @return Response
     */
    public function resultsCSV(Category $category): Response
    {
        $candidates = $category->candidates()
            ->where('status', 'approved')
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        $totalVotes = $category->votes()->count();

        // Create CSV content
        $csv = "Category: {$category->name}\n";
        $csv .= "Export Date: " . now()->format('Y-m-d H:i:s') . "\n";
        $csv .= "Total Votes: {$totalVotes}\n\n";
        $csv .= "Rank,Candidate Name,Votes,Percentage\n";

        $rank = 1;
        foreach ($candidates as $candidate) {
            $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 2) : 0;
            $csv .= "{$rank}," . 
                    '"' . ($candidate->full_name ?? $candidate->name) . '",' . 
                    "{$candidate->votes_count},{$percentage}%\n";
            $rank++;
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="results_' . str_slug($category->name) . '_' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Export applications as CSV
     *
     * @param Request $request
     * @return Response
     */
    public function applicationsCSV(Request $request): Response
    {
        $query = Application::with(['user', 'category']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $applications = $query->get();

        $csv = "Applications Report\n";
        $csv .= "Export Date: " . now()->format('Y-m-d H:i:s') . "\n";
        $csv .= "Total Applications: " . $applications->count() . "\n\n";
        $csv .= "Student Name,Email,Category,Status,Submitted Date,Reviewed Date\n";

        foreach ($applications as $app) {
            $csv .= '"' . $app->user->name . '",' .
                    '"' . $app->user->email . '",' .
                    '"' . $app->category->name . '",' .
                    "{$app->status}," .
                    $app->created_at->format('Y-m-d') . ',' .
                    ($app->reviewed_at?->format('Y-m-d') ?? 'N/A') . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications_' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Export statistics summary
     *
     * @return Response
     */
    public function statisticsCSV(): Response
    {
        $stats = [
            'Total Applications' => Application::count(),
            'Pending Applications' => Application::where('status', 'pending')->count(),
            'Approved Applications' => Application::where('status', 'approved')->count(),
            'Rejected Applications' => Application::where('status', 'rejected')->count(),
            'Registered Candidates' => Application::where('status', 'registered')->count(),
        ];

        $csv = "System Statistics\n";
        $csv .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $csv .= "Metric,Count\n";

        foreach ($stats as $metric => $count) {
            $csv .= "{$metric},{$count}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="statistics_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
