<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Vote Controller
 * Handles voting functionality with rate limiting
 */
class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('throttle.vote');
    }

    /**
     * Cast a vote for a candidate
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = auth()->user();
        $candidate = Candidate::findOrFail($validated['candidate_id']);
        $category = Category::findOrFail($validated['category_id']);

        // Check if voting period is active
        if ($category->status !== 'voting') {
            return response()->json([
                'success' => false,
                'message' => 'Voting is not currently active for this category.',
            ], 422);
        }

        // Check if user already voted in this category
        $existingVote = Vote::where('user_id', $user->id)
            ->where('category_id', $category->id)
            ->first();

        if ($existingVote) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted in this category. You can only vote once per category.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create the vote
            $vote = Vote::create([
                'user_id' => $user->id,
                'candidate_id' => $candidate->id,
                'category_id' => $category->id,
            ]);

            // Log the vote
            AuditLog::log(
                $user->id,
                'vote_cast',
                'Vote',
                $vote->id,
                [
                    'candidate_id' => $candidate->id,
                    'category_id' => $category->id,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your vote has been recorded successfully.',
                'data' => [
                    'vote_id' => $vote->id,
                    'candidate' => $candidate->name,
                    'category' => $category->name,
                    'created_at' => $vote->created_at->toIso8601String(),
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record your vote: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's voting history
     *
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        $votes = Vote::where('user_id', auth()->id())
            ->with(['candidate', 'category'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (Vote $vote) {
                return [
                    'id' => $vote->id,
                    'candidate_name' => $vote->candidate->full_name ?? $vote->candidate->name,
                    'category_name' => $vote->category->name,
                    'voted_at' => $vote->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $votes,
        ]);
    }

    /**
     * Get category results
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function results(Category $category): JsonResponse
    {
        if ($category->status === 'nomination') {
            return response()->json([
                'success' => false,
                'message' => 'Voting has not started for this category.',
            ], 422);
        }

        $results = $category->candidates()
            ->where('status', 'approved')
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get()
            ->map(function ($candidate) use ($category) {
                $totalVotes = $category->votes()->count();
                $candidateVotes = $candidate->votes_count;
                $percentage = $totalVotes > 0 ? round(($candidateVotes / $totalVotes) * 100, 2) : 0;

                return [
                    'id' => $candidate->id,
                    'name' => $candidate->full_name ?? $candidate->name,
                    'votes' => $candidateVotes,
                    'percentage' => $percentage,
                    'position' => $candidate->position_number,
                ];
            });

        return response()->json([
            'success' => true,
            'category' => $category->name,
            'total_votes' => $category->votes()->count(),
            'data' => $results,
        ]);
    }
}
