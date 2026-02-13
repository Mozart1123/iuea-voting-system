<?php

namespace App\Http\Controllers;

use App\Models\ElectionCategory;
use Illuminate\Http\JsonResponse;

/**
 * Public API for election categories.
 * Displays only active categories with future deadlines for students.
 */
class PublicCategoryController extends Controller
{
    /**
     * Display a listing of active election categories.
     * Only shows categories that are active and have not passed their deadline.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = ElectionCategory::where('is_active', true)
            ->where('application_deadline', '>', now())
            ->orderBy('application_deadline', 'asc')
            ->get()
            ->map(function (ElectionCategory $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'deadline' => $category->application_deadline->format('M d, Y'),
                    'deadline_iso' => $category->application_deadline->toIso8601String(),
                    'has_passed_deadline' => $category->hasPassedDeadline(),
                    'votes_count' => $category->getVotesCount(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Display the specified election category.
     *
     * @param ElectionCategory $category
     * @return JsonResponse
     */
    public function show(ElectionCategory $category): JsonResponse
    {
        if (!$category->is_active && (!auth()->check() || !auth()->user()->isAdmin())) {
            return response()->json([
                'success' => false,
                'message' => 'This category is not available.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $category->icon,
                'deadline' => $category->application_deadline->format('M d, Y'),
                'deadline_iso' => $category->application_deadline->toIso8601String(),
                'has_passed_deadline' => $category->hasPassedDeadline(),
                'votes_count' => $category->getVotesCount(),
                'applications_count' => $category->applications()->count(),
            ],
        ]);
    }
}
