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
        
        // Data for Vote Now / Elections - Restricted by Faculty
        $categories = Category::where('status', 'voting')
            ->where(function($q) use ($user) {
                $q->whereNull('faculty_restriction')
                  ->orWhere('faculty_restriction', $user->faculty);
            })
            ->with(['candidates' => function($query) {
                $query->where('status', 'approved')->orderBy('position_number')->withCount('votes');
            }])
            ->withCount('votes')
            ->get();

        $votedCategoryIds = Vote::where('user_id', $user->id)
            ->pluck('category_id')
            ->toArray();
            
        // Results (categories visible to the user)
        $results = Category::whereIn('status', ['voting', 'completed', 'closed'])
            ->where(function($q) use ($user) {
                $q->whereNull('faculty_restriction')
                  ->orWhere('faculty_restriction', $user->faculty);
            })
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

        $electionEndTime = \App\Models\SystemSetting::where('key', 'election_end_time')->value('value');
        $isEnded = (\App\Models\SystemSetting::where('key', 'election_ended')->first()->value ?? '0') == '1';

        return view('dashboard.index', [
            'user' => $user,
            'categories' => $categories,
            'votedCategoryIds' => $votedCategoryIds,
            'results' => $results,
            'myVotes' => $myVotes,
            'totalCategories' => $categories->count(),
            'votesCount' => count($votedCategoryIds),
            'pendingVotes' => $categories->count() - count($votedCategoryIds),
            'electionEndTime' => $electionEndTime,
            'isEnded' => $isEnded,
        ]);
    }

    /**
     * Show active elections and candidates
     */
    public function elections()
    {
        $user = Auth::user();
        $categories = Category::where('status', 'voting')
            ->where(function($q) use ($user) {
                $q->whereNull('faculty_restriction')
                  ->orWhere('faculty_restriction', $user->faculty);
            })
            ->with(['candidates' => function($query) {
                $query->where('status', 'approved')->orderBy('position_number')->withCount('votes');
            }])
            ->withCount('votes')
            ->get();

        $votedCategoryIds = Vote::where('user_id', Auth::id())
            ->pluck('category_id')
            ->toArray();

        $electionEndTime = \App\Models\SystemSetting::where('key', 'election_end_time')->value('value');
        return view('dashboard.elections', compact('categories', 'votedCategoryIds', 'electionEndTime'));
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
            $candidate = Candidate::lockForUpdate()->with('category')->findOrFail($request->candidate_id);
            $user = Auth::user();
            $userId = $user->id;

            // Check if global election has ended
            $isEnded = (\App\Models\SystemSetting::where('key', 'election_ended')->first()->value ?? '0') == '1';
            if ($isEnded) {
                return back()->with('error', 'The election has officially ended. No more votes can be cast.');
            }

            // Faculty Restriction Check
            if ($candidate->category->faculty_restriction && $candidate->category->faculty_restriction !== $user->faculty) {
                return back()->with('error', 'You are not eligible to vote in this faculty-restricted election.');
            }

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

                // Log the user out after voting and redirect to login page
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('success', '✅ Your vote for ' . $candidate->name . ' has been recorded successfully! Thank you for participating.');
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
     * Show nomination page
     */
    public function nomination()
    {
        $user = Auth::user();
        $categoriesInNomination = Category::where('status', 'nomination')
            ->where(function($q) use ($user) {
                $q->whereNull('faculty_restriction')
                  ->orWhere('faculty_restriction', $user->faculty);
            })
            ->get();
        
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

        \App\Models\User::notifyAdmins([
            'title' => 'New Candidacy',
            'message' => "{$user->name} has applied for an election.",
            'icon' => 'fas fa-user-graduate',
            'type' => 'info'
        ]);

        return redirect()->route('dashboard.nomination')->with('success', 'Your candidacy has been submitted and is awaiting approval.');
    }
    public function liveResults()
    {
        return view('dashboard.live-results');
    }
}

