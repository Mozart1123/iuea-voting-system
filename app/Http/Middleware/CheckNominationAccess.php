<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNominationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nominationEnabled = \App\Models\SystemSetting::where('key', 'nomination_enabled')->first();

        if (!$nominationEnabled || $nominationEnabled->value !== '1') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Nomination process is currently closed.'], 403);
            }
            return redirect()->route('dashboard.index')->with('error', 'The nomination process is currently closed by the administrator.');
        }

        return $next($request);
    }
}
