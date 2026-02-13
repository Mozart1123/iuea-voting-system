<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ElectionCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Admin Controller for managing election categories.
 * All endpoints require admin authentication.
 */
class CategoryController extends Controller
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
     * Display a listing of all election categories (admin view).
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ElectionCategory::class);

        $categories = ElectionCategory::with('creator')
            ->orderBy('application_deadline')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created election category.
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', ElectionCategory::class);

        try {
            $category = ElectionCategory::create([
                'name' => $request->validated('name'),
                'description' => $request->validated('description'),
                'icon' => $request->validated('icon'),
                'application_deadline' => $request->validated('application_deadline'),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Election category created successfully.',
                'data' => $category->load('creator'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified election category.
     *
     * @param ElectionCategory $category
     * @return JsonResponse
     */
    public function show(ElectionCategory $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json([
            'success' => true,
            'data' => $category->load('creator', 'applications'),
        ]);
    }

    /**
     * Update the specified election category.
     *
     * @param UpdateCategoryRequest $request
     * @param ElectionCategory $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, ElectionCategory $category): JsonResponse
    {
        $this->authorize('update', $category);

        try {
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Election category updated successfully.',
                'data' => $category->load('creator'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified election category.
     *
     * @param ElectionCategory $category
     * @return JsonResponse
     */
    public function destroy(ElectionCategory $category): JsonResponse
    {
        $this->authorize('delete', $category);

        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Election category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle the active status of a category.
     *
     * @param ElectionCategory $category
     * @return JsonResponse
     */
    public function toggleActive(ElectionCategory $category): JsonResponse
    {
        $this->authorize('update', $category);

        try {
            $category->update(['is_active' => !$category->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Category status updated.',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
