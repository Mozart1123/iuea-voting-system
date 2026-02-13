<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view any applications.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || true; // Admins can see all, students see their own
    }

    /**
     * Determine whether the user can view the application.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->isAdmin() || $user->id === $application->user_id;
    }

    /**
     * Determine whether the user can create an application.
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin(); // Only students can create applications
    }

    /**
     * Determine whether the user can update the application.
     */
    public function update(User $user, Application $application): bool
    {
        // Only admins or the owner can update
        return $user->isAdmin() || $user->id === $application->user_id;
    }

    /**
     * Determine whether the user can delete the application.
     */
    public function delete(User $user, Application $application): bool
    {
        // Students can only delete their own pending applications
        // Admins can always delete
        return $user->isAdmin() || ($user->id === $application->user_id && $application->status === 'pending');
    }

    /**
     * Determine whether the user can approve the application.
     */
    public function approve(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the application.
     */
    public function reject(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can register the application.
     */
    public function register(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }
}
