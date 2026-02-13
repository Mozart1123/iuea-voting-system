# Policies Configuration Guide

This guide explains how to properly register and use Laravel Policies for authorization in the Voter Registration system.

## What are Policies?

Policies are classes that organize authorization logic around a particular model or resource. They let you authorize actions like viewing, creating, updating, or deleting a resource.

## Registering Policies

### Method 1: Auto-Discovery (Recommended for Laravel 8+)

Laravel can automatically discover policies if they follow the naming convention:
- Model: `ElectionCategory`
- Policy: `ElectionCategoryPolicy`

This works by default in `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    // Policies will be auto-discovered
];
```

### Method 2: Manual Registration

If auto-discovery doesn't work, manually register policies in `app/Providers/AuthServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Models\ElectionCategory;
use App\Models\Application;
use App\Policies\ElectionCategoryPolicy;
use App\Policies\ApplicationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ElectionCategory::class => ElectionCategoryPolicy::class,
        Application::class => ApplicationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
```

## Using Policies in Controllers

### In the Controller Constructor

```php
public function __construct()
{
    $this->middleware('auth:sanctum');
    $this->authorizeResource(ElectionCategory::class);
}
```

### Using the `authorize()` Method

```php
public function update(Request $request, ElectionCategory $category)
{
    $this->authorize('update', $category);
    
    // Your update logic here
}
```

### Using the `Gate` Facade

```php
use Illuminate\Support\Facades\Gate;

if (Gate::denies('update', $category)) {
    return response()->json(['message' => 'Unauthorized'], 403);
}
```

## Policy Methods

### ElectionCategoryPolicy

```php
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
```

### ApplicationPolicy

```php
class ApplicationPolicy
{
    /**
     * Determine whether the user can view any applications.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || true;
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
     * Determine whether the user can delete the application.
     */
    public function delete(User $user, Application $application): bool
    {
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
```

## Testing Policies

### Using PHPUnit

```php
class ElectionCategoryPolicyTest extends TestCase
{
    /** @test */
    public function admin_can_create_category()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        $this->assertTrue(
            Gate::forUser($admin)->allows('create', ElectionCategory::class)
        );
    }

    /** @test */
    public function student_cannot_create_category()
    {
        $student = User::factory()->create(['is_admin' => false]);
        
        $this->assertFalse(
            Gate::forUser($student)->allows('create', ElectionCategory::class)
        );
    }
}
```

### Using `authorize()` Helper

```php
// In tests
$user = User::factory()->create();
$category = ElectionCategory::factory()->create();

// Check if user can view
Gate::forUser($user)->authorize('view', $category);

// Check if user can update
Gate::forUser($user)->denies('update', $category);
```

## Best Practices

1. **Always use Policies** for model-related authorization
2. **Keep logic in Policies** - Don't hard-code checks in controllers
3. **Use meaningful method names** - `canApprove()` is clearer than `canDoAction()`
4. **Document your logic** - Add comments explaining authorization rules
5. **Test your policies** - Write tests for each authorization scenario
6. **Use Gates for non-model actions** - Policies are for model-specific logic

## Troubleshooting

### "Unauthorized" Error When Using Policy

1. Check that the policy is registered correctly
2. Verify the method name matches your authorization check
3. Ensure the user object is passed correctly to the policy
4. Check that `isAdmin()` method exists on the User model

### Policy Not Being Found

1. Verify the policy file exists in `app/Policies/`
2. Check the namespace is correct: `namespace App\Policies;`
3. Run: `php artisan cache:clear`
4. Verify auto-discovery is enabled in `AuthServiceProvider.php`

### AuthorizationException Not Caught

If you need custom error handling:

```php
try {
    $this->authorize('update', $category);
} catch (AuthorizationException $e) {
    return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
    ], 403);
}
```

---

**Last Updated:** February 12, 2026
