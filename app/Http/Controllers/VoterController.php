<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Voter;
use App\Services\VotingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoterController extends Controller
{
    protected $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    /**
     * Show the initial entry screen where student enters ID.
     */
    public function showEntry()
    {
        return view('voter.entry');
    }

    /**
     * Process entry and redirect to ballot if eligibility is verified.
     */
    public function processEntry(Request $request)
    {
        $request->validate([
            'registration_number' => ['required', 'string', 'regex:/^[0-9\/A-Z]+$/'],
            'full_name' => ['required', 'string', 'max:255'],
        ]);

        if (Voter::where('registration_number', $request->registration_number)->exists()) {
            return back()->with('error', 'This student has already voted.')->withInput();
        }

        session([
            'voter_reg' => $request->registration_number,
            'voter_name' => $request->full_name,
            'voter_verified' => true
        ]);

        return redirect()->route('voter.ballot');
    }

    /**
     * Show the voting ballot.
     */
    public function showBallot()
    {
        if (!session('voter_verified')) {
            return redirect()->route('voter.entry');
        }

        $categories = Category::where('is_active', true)
            ->with('candidates')
            ->get();

        return view('voter.ballot', compact('categories'));
    }

    /**
     * Submit the vote.
     */
    public function submitVote(Request $request)
    {
        if (!session('voter_verified')) {
            return redirect()->route('voter.entry');
        }

        $categories = Category::where('is_active', true)->pluck('id')->toArray();
        
        // Validation: At least one category must be voted
        $hasVote = false;
        foreach ($categories as $catId) {
            if ($request->input("cat_{$catId}")) {
                $hasVote = true;
                break;
            }
        }

        if (!$hasVote) {
            return back()->with('error', 'You must select at least one candidate to submit your vote.');
        }

        // Prepare data for service
        $voteData = [
            'registration_number' => session('voter_reg'),
            'full_name' => session('voter_name'),
            'votes' => []
        ];

        foreach ($categories as $catId) {
            $candidateId = $request->input("cat_{$catId}");
            if ($candidateId) {
                $voteData['votes'][$catId] = $candidateId;
            }
        }

        try {
            $this->votingService->castBallot($voteData);
            
            // Clear session after success
            session()->forget(['voter_reg', 'voter_name', 'voter_verified']);
            
            return redirect()->route('voter.success');
        } catch (\Exception $e) {
            return back()->with('error', 'System error occurred while saving your vote. Please contact supervisor.');
        }
    }

    /**
     * Show success message and redirect back.
     */
    public function showSuccess()
    {
        return view('voter.success');
    }
}
