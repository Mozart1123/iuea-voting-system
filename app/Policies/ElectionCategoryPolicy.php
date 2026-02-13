<?php

namespace App\Policies;

use App\Models\ElectionCategory;
use App\Models\User;

class ElectionCategoryPolicy
{
    /**
     * Determine whether the user can view any election categories.
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view public categories
    }

    /**
     * Determine whether the user can view the election category.
     */
    public function view(User $user, ElectionCategory $category): bool
    {
        return $category->is_active || $user->isAdmin();
    }

    /**
     * Determine whether the user can create election categories.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the election category.
     */
    public function update(User $user, ElectionCategory $category): bool
    {
        return $user->isAdmin() && $user->id === $category->created_by;
    }

    /**
     * Determine whether the user can delete the election category.
     */
    public function delete(User $user, ElectionCategory $category): bool
    {
        return $user->isAdmin() && $user->id === $category->created_by;
    }
}
