<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Mail\ApplicationApprovedMail;
use App\Mail\ApplicationRejectedMail;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Admin Controller for managing applications.
 * All endpoints require admin authentication.
 */
class ApplicationController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    /**
     * Display a listing of all applications with filters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);

        $query = Application::with(['user', 'category', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Sort options
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $applications = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $applications->items(),
            'pagination' => [
                'total' => $applications->total(),
                'per_page' => $applications->perPage(),
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
            ],
        ]);
    }

    /**
     * Display the specified application.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function show(Application $application): JsonResponse
    {
        $this->authorize('view', $application);

        return response()->json([
            'success' => true,
            'data' => $application->load(['user', 'category', 'reviewer']),
        ]);
    }

    /**
     * Approve the specified application.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function approve(Application $application): JsonResponse
    {
        $this->authorize('approve', $application);

        if ($application->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Application is already approved.',
            ], 422);
        }

        try {
            $application->approve(auth()->user());

            // Send approval email
            Mail::to($application->user->email)->queue(new ApplicationApprovedMail($application));
            // Clear cache
            StatisticsService::clearCache();
            // Clear cache
            StatisticsService::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Application approved successfully.',
                'data' => $application->load(['user', 'category', 'reviewer']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject the specified application.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function reject(Application $application): JsonResponse
    {
        $this->authorize('reject', $application);

        if ($application->status === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Application is already rejected.',
            ], 422);
        }

        try {
            $application->reject(auth()->user());

            // Send rejection email
            Mail::to($application->user->email)->queue(new ApplicationRejectedMail($application));

            // Clear cache
            StatisticsService::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully.',
                'data' => $application->load(['user', 'category', 'reviewer']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register the specified application (mark as registered).
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function register(Application $application): JsonResponse
    {
        $this->authorize('register', $application);

        // Only approved applications can be registered
        if ($application->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved applications can be registered.',
            ], 422);
        }

        try {
            $application->register();

            return response()->json([
                'success' => true,
                'message' => 'Application registered successfully.',
                'data' => $application->load(['user', 'category', 'reviewer']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified application.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function destroy(Application $application): JsonResponse
    {
        $this->authorize('delete', $application);

        try {
            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get statistics for applications.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {StatisticsService::getApplicationStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'cached' => truee,
            'data' => $stats,
        ]);
    }
}
