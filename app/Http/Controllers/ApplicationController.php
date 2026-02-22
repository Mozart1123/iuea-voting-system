<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ElectionCategory;
use App\Mail\ApplicationSubmittedMail;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Student API for managing their election applications.
 * All endpoints require authentication.
 */
class ApplicationController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the authenticated user's applications.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $applications = auth()->user()->applications()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (Application $app) {
                return [
                    'id' => $app->id,
                    'category_id' => $app->category_id,
                    'category_name' => $app->category->name,
                    'status' => $app->status,
                    'statement' => $app->statement,
                    'manifesto_url' => $app->manifesto_url,
                    'submitted_date' => $app->created_at->format('Y-m-d'),
                    'submitted_date_iso' => $app->created_at->toIso8601String(),
                    'reviewed_at' => $app->reviewed_at?->toIso8601String(),
                    'reviewer_name' => $app->reviewer?->name,
                    'can_withdraw' => $app->canBeWithdrawn(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $applications,
        ]);
    }

    /**
     * Store a new application for the authenticated user.
     *
     * @param StoreApplicationRequest $request
     * @return JsonResponse
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        // Additional validation: check deadline hasn't passed
        $category = ElectionCategory::findOrFail($request->validated('category_id'));

        if ($category->hasPassedDeadline()) {
            return response()->json([
                'success' => false,
                'message' => 'The application deadline for this category has passed.',
            ], 422);
        }

        try {
            $application = Application::create([
                'user_id' => auth()->id(),
                'category_id' => $request->validated('category_id'),
                'statement' => $request->validated('statement'),
                'manifesto_url' => $request->validated('manifesto_url'),
                'status' => 'pending',
            ]);

            // Send confirmation email
            Mail::to(auth()->user()->email)->queue(new ApplicationSubmittedMail($application));

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully. Administration will review it shortly.',
                'data' => [
                    'id' => $application->id,
                    'category_id' => $application->category_id,
                    'category_name' => $application->category->name,
                    'status' => $application->status,
                    'submitted_date' => $application->created_at->format('Y-m-d'),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified application.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function show(Application $application): JsonResponse
    {
        $this->authorize('view', $application);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $application->id,
                'category_id' => $application->category_id,
                'category_name' => $application->category->name,
                'status' => $application->status,
                'statement' => $application->statement,
                'manifesto_url' => $application->manifesto_url,
                'submitted_date' => $application->created_at->format('Y-m-d'),
                'submitted_date_iso' => $application->created_at->toIso8601String(),
                'reviewed_at' => $application->reviewed_at?->toIso8601String(),
                'reviewer_name' => $application->reviewer?->name,
                'can_withdraw' => $application->canBeWithdrawn(),
            ],
        ]);
    }

    /**
     * Delete (withdraw) the specified application.
     * Only pending applications can be withdrawn by the student.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function destroy(Application $application): JsonResponse
    {
        $this->authorize('delete', $application);

        if (!$application->canBeWithdrawn()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending applications can be withdrawn. Your application status is: ' . $application->status,
            ], 422);
        }

        try {
            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application withdrawn successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to withdraw application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if the user has already applied to a category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkApplication(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|exists:election_categories,id',
        ]);

        $exists = auth()->user()->applications()
            ->where('category_id', $request->input('category_id'))
            ->exists();

        return response()->json([
            'success' => true,
            'has_applied' => $exists,
        ]);
    }

    /**
     * Get stats about user's applications.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $stats = StatisticsService::getUserApplicationStats(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $stats,
            'cached' => true,
        ]);
    }
}
