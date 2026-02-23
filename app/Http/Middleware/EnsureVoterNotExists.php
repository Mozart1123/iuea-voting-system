<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Voter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVoterNotExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $registrationNumber = $request->input('registration_number');

        if ($registrationNumber && Voter::where('registration_number', $registrationNumber)->exists()) {
            return back()->with('error', 'This student has already voted. Access denied.')->withInput();
        }

        return $next($request);
    }
}
