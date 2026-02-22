<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ElectionCategory;
use Illuminate\Support\Facades\Cache;

class StatisticsService
{
    /**
     * Get cached application statistics
     * Cache expires after 5 minutes
     */
    public static function getApplicationStats(): array
    {
        return Cache::remember('app_stats', now()->addMinutes(5), function () {
            return [
                'total' => Application::count(),
                'pending' => Application::where('status', 'pending')->count(),
                'approved' => Application::where('status', 'approved')->count(),
                'rejected' => Application::where('status', 'rejected')->count(),
                'registered' => Application::where('status', 'registered')->count(),
            ];
        });
    }

    /**
     * Get user's application statistics
     * Cache expires after 10 minutes
     */
    public static function getUserApplicationStats(int $userId): array
    {
        return Cache::remember('user_app_stats_' . $userId, now()->addMinutes(10), function () use ($userId) {
            $applications = Application::where('user_id', $userId);

            return [
                'total_applications' => $applications->count(),
                'pending' => $applications->where('status', 'pending')->count(),
                'approved' => $applications->where('status', 'approved')->count(),
                'rejected' => $applications->where('status', 'rejected')->count(),
                'registered' => $applications->where('status', 'registered')->count(),
            ];
        });
    }

    /**
     * Get cached category statistics
     * Cache expires after 5 minutes
     */
    public static function getCategoryStats(): array
    {
        return Cache::remember('category_stats', now()->addMinutes(5), function () {
            $categories = ElectionCategory::all();

            return [
                'total_categories' => $categories->count(),
                'active_categories' => $categories->where('is_active', true)->count(),
                'inactive_categories' => $categories->where('is_active', false)->count(),
                'categories' => $categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'applications_count' => $cat->applications()->count(),
                        'is_active' => $cat->is_active,
                    ];
                }),
            ];
        });
    }

    /**
     * Clear all stat caches (call after updates)
     */
    public static function clearCache(): void
    {
        Cache::forget('app_stats');
        Cache::forget('category_stats');
        // Clear user stats for all users (better approach: use cache tags)
        Cache::tags(['user_stats'])->flush();
    }

    /**
     * Clear user-specific cache
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget('user_app_stats_' . $userId);
    }
}
