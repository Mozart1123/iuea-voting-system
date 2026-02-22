<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAuditTrail
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log API requests that modify data
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH']) && str_starts_with($request->path(), 'api/')) {
            \Log::info('Audit action', [
                'user_id' => auth()->id(),
                'method' => $request->method(),
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);

            // Log application submission
            if ($request->path() === 'api/applications' && $request->isMethod('post')) {
                AuditLog::log(
                    auth()->id(),
                    'application_submitted',
                    'Application',
                    null,
                    $request->all()
                );
            }

            // Log vote
            if (str_contains($request->path(), 'api') && str_contains($request->path(), 'vote') && $request->isMethod('post')) {
                AuditLog::log(
                    auth()->id(),
                    'vote_cast',
                    'Vote',
                    null,
                    ['category' => $request->input('category_id')]
                );
            }

            // Log admin actions
            if (str_starts_with($request->path(), 'api/admin/')) {
                $action = match (true) {
                    str_contains($request->path(), 'approve') => 'application_approved',
                    str_contains($request->path(), 'reject') => 'application_rejected',
                    str_contains($request->path(), 'register') => 'application_registered',
                    $request->isMethod('post') => 'created',
                    $request->isMethod('put') => 'updated',
                    $request->isMethod('delete') => 'deleted',
                    default => 'action_performed',
                };

                AuditLog::log(
                    auth()->id(),
                    $action,
                    'Admin',
                    null,
                    ['path' => $request->path()]
                );
            }
        }

        return $response;
    }
}
