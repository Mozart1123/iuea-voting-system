<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleVotes
{
    /**
     * Handle an incoming request.
     * Limit voting: 1 vote per minute per user, 30 votes per hour per IP
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user) {
            $rateLimiter = app(RateLimiter::class);
            
            // Limit: 1 vote per minute per user
            if ($rateLimiter->tooManyAttempts('vote:' . $user->id, 1)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are voting too quickly. Please try again in ' . 
                                $rateLimiter->availableIn('vote:' . $user->id) . ' seconds.',
                ], 429);
            }

            // Limit: 30 votes per hour per IP
            if ($rateLimiter->tooManyAttempts('vote:ip:' . $request->ip(), 30)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many votes from this IP address. Please try again later.',
                ], 429);
            }

            // Increment counters
            $rateLimiter->hit('vote:' . $user->id, 60); // 1 minute
            $rateLimiter->hit('vote:ip:' . $request->ip(), 3600); // 1 hour
        }

        return $next($request);
    }
}
