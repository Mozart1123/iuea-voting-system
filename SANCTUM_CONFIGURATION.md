# Laravel Sanctum Configuration Guide

This guide explains how to properly configure Laravel Sanctum for API authentication in IUEA GuildVote.

## What is Sanctum?

Laravel Sanctum provides a light-weight authentication system for SPAs (Single Page Applications) and mobile applications. It issues API tokens for stateless, token-based authentication.

## Installation

### Step 1: Install Sanctum

```bash
composer require laravel/sanctum
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

This creates:
- `config/sanctum.php` - Configuration file
- Migration file for tokens table

### Step 3: Run Migration

```bash
php artisan migrate
```

This creates the `personal_access_tokens` table for storing API tokens.

## Configuration

### config/sanctum.php

Key configuration options:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This value defines the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request.
    |
    */
    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value defines the number of minutes after which an issued token
    | is considered expired.
    |
    */
    'expiration' => null, // No expiration by default

    /*
    |--------------------------------------------------------------------------
    | Token Prefix
    |--------------------------------------------------------------------------
    |
    | Sanctum can prefix the generated tokens. You can modify this value.
    |
    */
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | CORS Allowed Origins
    |--------------------------------------------------------------------------
    |
    | List of origins that are allowed to make requests to your API.
    |
    */
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];
```

### .env Configuration

Add/update these variables in your `.env`:

```env
# Application
APP_NAME="IUEA GuildVote"
APP_URL=http://localhost:8000

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:8000,127.0.0.1:8000
SESSION_DOMAIN=localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_db
DB_USERNAME=root
DB_PASSWORD=
```

## User Model Setup

Ensure your User model uses the `HasApiTokens` trait:

```php
<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    
    // ... rest of model
}
```

This trait provides methods:
- `createToken($name)` - Create an API token
- `tokens()` - Relationship to tokens
- `currentAccessToken()` - Get current token

## Creating Tokens

### In Controller (After Login)

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Response;

class LoginController
{
    public function login()
    {
        // Authenticate user (with request validation)
        $user = User::where('email', request('email'))->first();

        if (Hash::check(request('password'), $user->password)) {
            // Create API token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout()
    {
        // Revoke current token
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
```

### In Tinker (For Testing)

```bash
php artisan tinker

# Create token for user ID 2
>>> $user = User::find(2);
>>> $token = $user->createToken('api-token')->plainTextToken;
>>> $token
=> "2|v9Ab3c5DeF7gHiJkL8mNoPqRsT0uVwXyZ"
```

## Using Tokens in Requests

### JavaScript/Frontend

```javascript
// Get token from somewhere (localStorage, meta tag, etc.)
const token = localStorage.getItem('auth-token');

// Use in API request
fetch('/api/applications', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
    },
    method: 'GET',
})
    .then(response => response.json())
    .then(data => console.log(data));
```

### cURL Command

```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/applications
```

### Postman

1. Open "Authorization" tab
2. Select "Bearer Token" from dropdown
3. Paste your token in "Token" field
4. Send request

## Token Scopes (Advanced)

Scopes allow you to limit token permissions:

```php
// Create token with specific scopes
$token = $user->createToken('api-token', ['read'])->plainTextToken;

// In protected routes, check scope
Route::middleware(['auth:sanctum', 'scope:read'])->get('/applications', ...);

// In Middleware
if (!$request->user()->tokenCan('read')) {
    abort(403, 'Insufficient permissions');
}
```

## Protecting Routes

Routes using Sanctum:

```php
// Protect with Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // These routes require a valid token
    Route::get('/user', function() { ... });
    Route::get('/applications', [ApplicationController::class, 'index']);
});

// Admin-only routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/categories', [AdminCategoryController::class, 'index']);
});
```

## Error Handling

### Unauthorized (401)

User is not authenticated:

```json
{
    "message": "Unauthenticated."
}
```

**Solution:** Include valid token in Authorization header

### Forbidden (403)

User lacks necessary permissions:

```json
{
    "message": "Forbidden.",
    "success": false
}
```

**Solution:** Check user role or token scopes

## Testing Authentication

### Check Current User

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/user
```

Expected response:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "roles": ["student"]
    }
}
```

### Test Protected Endpoint

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/applications
```

### Test with Invalid Token

```bash
curl -H "Authorization: Bearer invalid-token" \
     http://localhost:8000/api/applications
```

Expected: 401 Unauthorized

## Common Issues & Solutions

### "Unauthenticated" Error with Valid Token

1. **Check token format** - Use `Bearer YOUR_TOKEN`
2. **Check Authorization header** - Case-sensitive
3. **Verify user still exists** in database
4. **Check token hasn't been revoked** - Look in `personal_access_tokens`

### CORS Errors

1. **Update .env SANCTUM_STATEFUL_DOMAINS** - Include your domain
2. **Check config/sanctum.php** - Stateful domains must match
3. **Enable CORS** - May need additional CORS middleware

### Token Expiration

By default, Sanctum tokens don't expire. To enable expiration:

```php
// In config/sanctum.php
'expiration' => 60 * 24, // 1 day in minutes

// Or create token with custom expiration
$token = $user->createToken('api-token')
    ->withExpiresAt(now()->addHours(1))
    ->plainTextToken;
```

## Advanced: Custom Middleware

Create custom middleware to handle Sanctum tokens:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Check for token
        if (!$request->bearerToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Bearer token required',
            ], 401);
        }

        return $next($request);
    }
}
```

Register in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'verify-token' => \App\Http\Middleware\VerifyApiToken::class,
];
```

## Logging Out & Revoking Tokens

### Revoke Current Token

```php
// In controller
auth()->user()->currentAccessToken()->delete();
```

### Revoke All User Tokens

```php
// Logout everywhere
auth()->user()->tokens()->delete();
```

## Best Practices

1. **Store tokens securely**
   - Use HttpOnly cookies for web apps
   - Use secure storage in mobile apps
   - Never expose in URLs

2. **Set appropriate expiration**
   - Short-lived for sensitive operations
   - Longer for less critical apps
   - Implement refresh tokens for long sessions

3. **Use HTTPS in production**
   - Always use HTTPs to protect tokens in transit
   - Enable secure flag on cookies

4. **Implement token rotation**
   - Periodically issue new tokens
   - Revoke old ones

5. **Log token creation/revocation**
   - Track who created/deleted tokens
   - Monitor for suspicious activity

## Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Sanctum GitHub Repository](https://github.com/laravel/sanctum)
- [API Token Authentication](https://laravel.com/docs/sanctum#how-it-works)
- [CORS Configuration](https://laravel.com/docs/cors)

---

**Last Updated:** February 12, 2026
