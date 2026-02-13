# Voter Registration Backend - Setup Instructions

## Overview
This document outlines the steps required to set up the complete backend for the **Voter Registration** feature in IUEA GuildVote.

## Prerequisites
- Laravel 10+ or 11+
- PHP 8.1+
- Composer
- Database (MySQL, PostgreSQL, or SQLite)
- Laravel Sanctum (API authentication)

## Step 1: Install Laravel Sanctum (if not already installed)

Sanctum is used for API authentication. Install it if you haven't already:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## Step 2: Update Environment Configuration

Ensure your `.env` file has the correct database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_db
DB_USERNAME=root
DB_PASSWORD=
```

## Step 3: Register Middleware

Add the `admin` middleware to your `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... other middleware ...
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

Or, if using Laravel 11, add it to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

## Step 4: Register Policies (Optional but Recommended)

If you want to use policies, register them in `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    ElectionCategory::class => ElectionCategoryPolicy::class,
    Application::class => ApplicationPolicy::class,
];
```

## Step 5: Run Migrations

This creates all necessary database tables:

```bash
php artisan migrate
```

The following tables will be created:
- **election_categories** - Stores election categories created by admins
- **applications** - Stores student applications for categories

## Step 6: Seed Election Categories (Optional)

Pre-populate your database with example election categories:

```bash
php artisan db:seed --class=ElectionCategorySeeder
```

Or seed all seeders:

```bash
php artisan db:seed
```

## Step 7: Update User Model

The User model has been updated to include new fields. Verify these fields exist in your users table:
- `student_id` (string, unique)
- `faculty` (string)
- `year_of_study` (integer)
- `role` (enum: student, admin)
- `is_admin` (boolean)

These are set in the migration: `database/migrations/2026_02_12_140000_add_fields_to_users_table.php`

## Step 8: Create an Admin User (if needed)

```bash
php artisan tinker

# In the Tinker shell:
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'student_id' => 'ADM001',
    'faculty' => 'Administration',
    'year_of_study' => 0,
    'role' => 'admin',
    'is_admin' => true,
]);

exit;
```

## Step 9: Include Frontend JavaScript

In your dashboard view (`resources/views/dashboard/index.blade.php`), add the script tag to include the voter registration JavaScript:

```html
<!-- Before closing </body> tag, add: -->
<script src="{{ asset('js/pages/voter-registration.js') }}"></script>
```

Or place the code inline in a `<script>` tag within the HTML.

## Step 10: Configure CSRF and Auth Headers

The JavaScript automatically looks for CSRF and auth tokens. Ensure your HTML includes:

```html
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="X-Auth-Token" content="{{ auth()->user()?->currentAccessToken()?->plainTextToken ?? '' }}">
```

Or update the `getCsrfToken()` and `getAuthToken()` functions in `voter-registration.js` to match your setup.

## API Endpoints Overview

### Public Endpoints (No Auth)
- `GET /api/categories` - List active categories with future deadlines
- `GET /api/categories/{id}` - Get details of a specific category

### Student Endpoints (Auth Required)
- `GET /api/applications` - Get student's applications
- `POST /api/applications` - Submit a new application
- `GET /api/applications/{id}` - Get details of a specific application
- `DELETE /api/applications/{id}` - Withdraw an application (must be pending)
- `GET /api/my-applications` - Alias for `/api/applications`

### Admin Endpoints (Auth + Admin Role Required)
- `GET /api/admin/categories` - List all categories
- `POST /api/admin/categories` - Create a category
- `PUT /api/admin/categories/{id}` - Update a category
- `DELETE /api/admin/categories/{id}` - Delete a category
- `PATCH /api/admin/categories/{id}/toggle-active` - Toggle category visibility

- `GET /api/admin/applications` - List all applications (with filters)
- `PATCH /api/admin/applications/{id}/approve` - Approve an application
- `PATCH /api/admin/applications/{id}/reject` - Reject an application
- `PATCH /api/admin/applications/{id}/register` - Register an approved application
- `DELETE /api/admin/applications/{id}` - Delete an application

## Testing the Setup

### 1. Test API Endpoints

Use Postman, curl, or the browser console to test:

```bash
# Get public categories
curl http://localhost:8000/api/categories

# Get authenticated user
curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8000/api/user
```

### 2. Test Frontend

Navigate to the Student Dashboard and click on "Voter Registration" to test:
- Loading categories
- Submitting applications
- Viewing application status
- Withdrawing applications

## Troubleshooting

### 401 Unauthorized Errors
- Ensure the auth token is being passed in the `Authorization` header
- Check that Sanctum middleware is enabled in your routes
- Verify the token is valid and not expired

### 403 Forbidden Errors
- For admin endpoints, verify the user has `is_admin = true` in the database
- Check that the `AdminMiddleware` is properly registered

### 422 Validation Errors
- Check the API response for detailed validation messages
- Ensure all required fields are provided
- Verify deadlines are in the future for new categories

### Database Errors
- Run `php artisan migrate:fresh --seed` to reset and reseed (development only)
- Check database connection in `.env`

## File Structure

```
app/
├── Models/
│   ├── ElectionCategory.php
│   └── Application.php
│   └── User.php (updated)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CategoryController.php
│   │   │   └── ApplicationController.php
│   │   ├── PublicCategoryController.php
│   │   └── ApplicationController.php
│   ├── Requests/
│   │   ├── StoreCategoryRequest.php
│   │   ├── UpdateCategoryRequest.php
│   │   └── StoreApplicationRequest.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
├── Policies/
│   ├── ElectionCategoryPolicy.php
│   └── ApplicationPolicy.php
database/
├── migrations/
│   ├── 2026_02_12_140000_add_fields_to_users_table.php
│   ├── 2026_02_12_140100_create_election_categories_table.php
│   └── 2026_02_12_140200_create_applications_table.php
└── seeders/
    └── ElectionCategorySeeder.php
routes/
├── api.php (created/updated)
└── web.php
resources/
├── js/
│   └── pages/
│       └── voter-registration.js
└── views/
    └── dashboard/
        └── index.blade.php (update to include script)
```

## Next Steps

1. ✅ Run migrations and seeders
2. ✅ Create admin user
3. ✅ Test API endpoints
4. ✅ Import JavaScript in views
5. ✅ Test frontend functionality
6. Add admin dashboard for reviewing applications (optional)
7. Add email notifications (optional)

## Support

For issues or questions about the implementation:
1. Check the inline code comments in each file
2. Review Laravel documentation: https://laravel.com/docs
3. Check API responses for error messages
4. Use Laravel Debug Bar for debugging

---

**Last Updated:** February 12, 2026
