<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get live voting statistics for the dashboard.
     */
    public function liveStats()
    {
        $categories = Category::where('is_active', true)
            ->where('status', 'voting')
            ->with(['candidates' => function($query) {
                $query->where('status', 'approved')->withCount('votes');
            }])
            ->get();

        $data = $categories->map(function($category) {
            $totalVotes = $category->candidates->sum('votes_count');
            
            return [
                'id' => $category->id,
                'name' => $category->name,
                'total_votes' => $totalVotes,
                'candidates' => $category->candidates->map(function($candidate) use ($totalVotes) {
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->name,
                        'photo' => $candidate->photo_path ? asset('storage/' . $candidate->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($candidate->name) . '&background=8B0000&color=fff',
                        'votes' => $candidate->votes_count,
                        'percentage' => $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0,
                    ];
                }),
            ];
        });

        // Overall stats
        $totalVoters = \App\Models\User::where('role', 'student')->count();
        $totalCast = Vote::count();
        $turnout = $totalVoters > 0 ? round(($totalCast / $totalVoters) * 100, 1) : 0;

        return response()->json([
            'categories' => $data,
            'stats' => [
                'total_cast' => $totalCast,
                'turnout' => $turnout,
                'election_end_time' => \App\Models\SystemSetting::where('key', 'election_end_time')->value('value')
            ]
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Content-Type', 'application/json');
    }
}
