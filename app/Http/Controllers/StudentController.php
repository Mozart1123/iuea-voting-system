<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Student Dashboard Index
     */
    public function index()
    {
        $user = Auth::user();
        
        // Data for Vote Now / Elections
        $categories = Category::where('status', 'voting')
            ->with(['candidates' => function($query) {
                $query->where('status', 'approved')->orderBy('position_number')->withCount('votes');
            }])
            ->withCount('votes')
            ->get();

        $votedCategoryIds = Vote::where('user_id', $user->id)
            ->pluck('category_id')
            ->toArray();
            
        // Results (all voting/completed categories)
        $results = Category::whereIn('status', ['voting', 'completed'])
            ->with(['candidates' => function($query) {
                $query->withCount('votes')->orderByDesc('votes_count'); // Fixed: Added withCount here
            }])
            ->withCount('votes')
            ->get();

        // User's private voting history
        $myVotes = Vote::where('user_id', $user->id)
            ->with(['candidate', 'category'])
            ->latest()
            ->get();

        return view('dashboard.index', [
            'user' => $user,
            'categories' => $categories,
            'votedCategoryIds' => $votedCategoryIds,
            'results' => $results,
            'myVotes' => $myVotes,
            'totalCategories' => $categories->count(),
            'votesCount' => count($votedCategoryIds),
            'pendingVotes' => $categories->count() - count($votedCategoryIds),
        ]);
    }

    /**
     * Show active elections and candidates
     */
    public function elections()
    {
        $categories = Category::where('status', 'voting')
            ->with(['candidates' => function($query) {
                $query->where('status', 'approved')->orderBy('position_number')->withCount('votes');
            }])
            ->withCount('votes')
            ->get();

        $votedCategoryIds = Vote::where('user_id', Auth::id())
            ->pluck('category_id')
            ->toArray();

        return view('dashboard.elections', compact('categories', 'votedCategoryIds'));
    }

    /**
     * Handle vote casting
     */
    public function vote(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        return \DB::transaction(function () use ($request) {
            $candidate = Candidate::lockForUpdate()->findOrFail($request->candidate_id);
            $userId = Auth::id();

            // Check if already voted in this category
            $existingVote = Vote::where('user_id', $userId)
                ->where('category_id', $candidate->category_id)
                ->lockForUpdate()
                ->first();

            if ($existingVote) {
                return back()->with('error', 'You have already cast your vote in this category.');
            }

            try {
                Vote::create([
                    'user_id' => $userId,
                    'candidate_id' => $candidate->id,
                    'category_id' => $candidate->category_id,
                ]);

                return back()->with('success', 'Your vote for ' . $candidate->name . ' has been recorded successfully!');
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) { // Unique constraint violation
                    return back()->with('error', 'You have already cast your vote in this category.');
                }
                throw $e;
            }
        });
    }

    /**
     * Show voting receipts
     */
    public function receipts()
    {
        $votes = Vote::where('user_id', Auth::id())
            ->with(['candidate', 'category'])
            ->latest()
            ->get();

        return view('dashboard.receipts', compact('votes'));
    }

    /**
     * Show security settings
     */
    public function security()
    {
        return view('dashboard.security');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password does not match our record.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Show nomination page
     */
    public function nomination()
    {
        $user = Auth::user();
        $categoriesInNomination = Category::where('status', 'nomination')->get();
        
        // Find if user already has a pending or approved nomination
        $existingNomination = Candidate::where('user_id', $user->id)->first();

        return view('dashboard.nomination', compact('categoriesInNomination', 'existingNomination'));
    }

    /**
     * Handle nomination submission
     */
    public function submitNomination(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'biography' => 'required|string|min:50',
            'faculty' => 'required|string',
            'student_class' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        // Check if user already applied
        $existing = Candidate::where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->status === 'rejected') {
                // If rejected, allow re-submission by removing the previous one
                $existing->delete();
            } else {
                return back()->with('error', 'You have already submitted a candidacy.');
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create([
            'category_id' => $request->category_id,
            'user_id' => $user->id,
            'name' => $user->name,
            'faculty' => $request->faculty,
            'student_class' => $request->student_class,
            'biography' => $request->biography,
            'photo_path' => $photoPath,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard.nomination')->with('success', 'Your candidacy has been submitted and is awaiting approval.');
    }
}
