<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Voter;

class CheckVoterSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if voter registration number is in session
        $regNumber = $request->session()->get('voter_reg_number');

        if (!$regNumber) {
            return redirect()->route('voter.login')->with('error', 'Please enter your registration number.');
        }

        // Check if already voted
        $voter = Voter::where('registration_number', $regNumber)->first();
        if ($voter && $voter->hasVoted()) {
            $request->session()->forget(['voter_reg_number', 'voter_full_name']);
            return redirect()->route('voter.login')->with('error', 'History Check: This registration number has already voted.');
        }

        return $next($request);
    }
}
