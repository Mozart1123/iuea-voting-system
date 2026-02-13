# IUEA GuildVote - Quick Reference Card

Quick cheat sheet for common tasks and commands.

## Table of Contents

1. [Essential Commands](#essential-commands)
2. [API Endpoints](#api-endpoints)
3. [Database Commands](#database-commands)
4. [Common cURL Examples](#common-curl-examples)
5. [Troubleshooting](#troubleshooting)

---

## Essential Commands

### Development

```bash
# Start development server
php artisan serve              # http://localhost:8000

# Run migrations
php artisan migrate            # Run all pending migrations
php artisan migrate:fresh      # Drop & recreate tables
php artisan migrate:rollback   # Undo last batch
php artisan migrate:reset      # Undo all migrations

# Seed database
php artisan db:seed                              # Run all seeders
php artisan db:seed --class=ElectionCategorySeeder  # Specific seeder
php artisan db:seed --force                      # In production

# Cache operations
php artisan config:cache       # Cache configuration
php artisan route:cache        # Cache routes
php artisan view:cache         # Cache views
php artisan cache:clear        # Clear cache

# Tinker (interactive shell)
php artisan tinker            # Start interactive PHP shell
```

### Testing

```bash
# Run tests
php artisan test              # Run all tests
php artisan test tests/Feature/ApplicationApiTest.php  # Specific test
php artisan test --coverage   # With code coverage

# Specific test methods
php artisan test --filter=test_student_can_submit_application
```

### Cleanup

```bash
# Clear logs
php artisan logs:clear

# Optimize application
php artisan optimize
php artisan optimize:clear

# Regenerate keys (use with caution!)
php artisan key:generate
```

---

## API Endpoints

### Public Endpoints (No Auth Required)

```
GET  /api/categories           # List active categories
GET  /api/categories/:id       # Get single category
```

### Student Endpoints (Auth Required)

```
POST   /api/applications       # Submit application
GET    /api/applications       # Get my applications
GET    /api/applications/:id   # Get single application
DELETE /api/applications/:id   # Withdraw application
GET    /api/applications/:id/check    # Check if can withdraw
GET    /api/applications/stats        # Get application stats
```

### Admin Endpoints (Auth + Admin Required)

```
# Categories
GET    /api/admin/categories           # List all categories (paginated)
POST   /api/admin/categories           # Create category
GET    /api/admin/categories/:id       # Get category details
PUT    /api/admin/categories/:id       # Update category
DELETE /api/admin/categories/:id       # Delete category
PATCH  /api/admin/categories/:id/toggle-active  # Toggle active status

# Applications
GET    /api/admin/applications         # List applications (filterable)
GET    /api/admin/applications/statistics  # Get statistics
PATCH  /api/admin/applications/:id/approve   # Approve application
PATCH  /api/admin/applications/:id/reject    # Reject application
PATCH  /api/admin/applications/:id/register  # Register application
```

---

## Database Commands

### Create Model with Migration

```bash
# Model + Migration
php artisan make:model ModelName -m

# Model + Migration + Factory
php artisan make:model ModelName -mf

# Model + Migration + Seeder
php artisan make:model ModelName -ms

# Controller + Model + Migration
php artisan make:model ModelName -mcr
```

### Create Other Files

```bash
# Migration only
php artisan make:migration create_table_name

# Seeder
php artisan make:seeder SeederName

# Controller
php artisan make:controller ControllerName

# Form Request
php artisan make:request StoreRequest

# Policy
php artisan make:policy PolicyName --model=ModelName

# Middleware
php artisan make:middleware MiddlewareName
```

### Query Database

```php
# In Tinker
>>> User::all()
>>> User::where('is_admin', true)->get()
>>> User::find(1)->applications
>>> Application::with('user', 'category')->get()
>>> ElectionCategory::where('is_active', true)->count()
```

### Reset Database

```bash
# Drop everything and migrate fresh
php artisan migrate:fresh

# Drop everything, migrate, and seed
php artisan migrate:fresh --seed

# Drop everything, migrate, and seed specific seeder
php artisan migrate:fresh --class=ElectionCategorySeeder
```

---

## Common cURL Examples

### Setup Token Variable

```bash
TOKEN="2|v9Ab3c5DeF7gHiJkL8mNoPqRsT0uVwXyZ"
```

### Get All Categories (Public)

```bash
curl -X GET http://localhost:8000/api/categories \
  -H "Content-Type: application/json"
```

### Create Application

```bash
curl -X POST http://localhost:8000/api/applications \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "statement": "I am a passionate leader...",
    "manifesto_url": "https://example.com"
  }'
```

### Get My Applications

```bash
curl -X GET http://localhost:8000/api/applications \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
```

### Withdraw Application

```bash
curl -X DELETE http://localhost:8000/api/applications/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
```

### Create Category (Admin)

```bash
curl -X POST http://localhost:8000/api/admin/categories \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Category Name",
    "description": "Description...",
    "icon": "star",
    "application_deadline": "2026-03-15T12:00:00Z",
    "is_active": true
  }'
```

### List All Applications (Admin)

```bash
# All pending
curl -X GET "http://localhost:8000/api/admin/applications?status=pending" \
  -H "Authorization: Bearer $ADMIN_TOKEN"

# For specific category
curl -X GET "http://localhost:8000/api/admin/applications?category_id=1" \
  -H "Authorization: Bearer $ADMIN_TOKEN"

# Sorted by date
curl -X GET "http://localhost:8000/api/admin/applications?sort_by=created_at&sort_order=desc" \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

### Approve Application (Admin)

```bash
curl -X PATCH http://localhost:8000/api/admin/applications/1/approve \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

### Reject Application (Admin)

```bash
curl -X PATCH http://localhost:8000/api/admin/applications/1/reject \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

### Register Application (Admin)

```bash
curl -X PATCH http://localhost:8000/api/admin/applications/1/register \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

---

## File Structure Reference

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CategoryController.php
│   │   │   └── ApplicationController.php
│   │   └── PublicCategoryController.php
│   │   └── ApplicationController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
│       ├── StoreCategoryRequest.php
│       ├── UpdateCategoryRequest.php
│       └── StoreApplicationRequest.php
├── Models/
│   ├── ElectionCategory.php
│   ├── Application.php
│   └── User.php
└── Policies/
    ├── ElectionCategoryPolicy.php
    └── ApplicationPolicy.php

database/
├── migrations/
│   ├── 2026_02_12_extension_add_fields_to_users.php
│   ├── 2026_02_12_create_election_categories.php
│   └── 2026_02_12_create_applications.php
└── seeders/
    └── ElectionCategorySeeder.php

routes/
└── api.php

resources/
├── js/
│   └── pages/
│       └── voter-registration.js
└── views/
    └── dashboard/
        └── index.blade.php
```

---

## Troubleshooting

### Token Creation (Tinker)

```bash
php artisan tinker

# Create token for user ID 2
>>> $user = User::find(2);
>>> $token = $user->createToken('api-token')->plainTextToken;
>>> $token

# Copy the token and use in API calls
```

### Check User is Admin

```bash
php artisan tinker

>>> User::find(1)->is_admin  # true/false
>>> User::where('is_admin', true)->first()
```

### View Database Records

```bash
php artisan tinker

>>> Application::all()
>>> ElectionCategory::all()
>>> User::all()
>>> Application::where('status', 'pending')->get()
```

### Verify Routes

```bash
php artisan route:list          # Show all routes
php artisan route:list --name=applications  # Filter routes
```

### Test API Response

```bash
# Test without token
curl -X GET http://localhost:8000/api/applications

# Should get 401 Unauthenticated error
```

### Check Error Logs

```bash
# Last 50 lines
tail -50 storage/logs/laravel.log

# Follow live logs
tail -f storage/logs/laravel.log
```

### Database Connection Test

```bash
php artisan tinker

>>> DB::connection()->getPdo();
>>> DB::select("SELECT 1");
```

### Clear Stale Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### View Active Database Connections

```bash
php artisan tinker

>>> DB::select("SHOW PROCESSLIST");
```

---

## Postman Quick Setup

### 1. Create Collection

- Name: `IUEA GuildVote`
- Create folders:
  - `Public`
  - `Student`
  - `Admin`

### 2. Set Base URL Variable

- Click "Variables" tab
- Add variable: `base_url` = `http://localhost:8000`
- Add variable: `token` = (your token)
- Add variable: `admin_token` = (admin token)

### 3. Use in Requests

```
GET {{base_url}}/api/categories
Authorization: Bearer {{token}}
```

### 4. Save Responses

- Click Save response → Save as example
- Useful for documentation

---

## Common Error Codes

| Code | Error | Solution |
|------|-------|----------|
| 200  | OK | Request successful |
| 201  | Created | Resource created successfully |
| 400  | Bad Request | Invalid request parameters |
| 401  | Unauthorized | Missing/invalid authentication token |
| 403  | Forbidden | Not authorized to perform action |
| 404  | Not Found | Resource doesn't exist |
| 422  | Unprocessable Entity | Validation failed |
| 500  | Server Error | Internal server error - check logs |

---

## Performance Tips

1. **Use Eager Loading**
   ```php
   // Bad
   $apps = Application::all();
   foreach ($apps as $app) { echo $app->user->name; } // N+1 queries

   // Good
   $apps = Application::with('user')->get(); // Single query
   ```

2. **Paginate Large Result Sets**
   ```php
   // Bad
   $apps = Application::all(); // Could be thousands

   // Good
   $apps = Application::paginate(15); // Chunks of 15
   ```

3. **Index Frequently Queried Columns**
   - `is_active`
   - `status`
   - `user_id`
   - `category_id`

4. **Cache Static Data**
   ```php
   $categories = Cache::rememberForever('categories', fn () =>
       ElectionCategory::where('is_active', true)->get()
   );
   ```

---

## Security Reminders

✅ **Always:**
- Use HTTPS in production
- Hash passwords with Bcrypt
- Validate all inputs
- Use prepared statements (Eloquent does this)
- Check authorization before actions
- Log security events
- Rotate API tokens periodically

❌ **Never:**
- Commit `.env` to version control
- Log sensitive data
- Trust user input
- Use MD5 for passwords
- Expose error details in production
- Store tokens in plain text

---

## Development Workflow

```bash
# 1. Create feature branch
git checkout -b feature/new-feature

# 2. Make changes
# ... code changes ...

# 3. Run tests
php artisan test

# 4. Commit changes
git add .
git commit -m "Add new feature"

# 5. Push to remote
git push origin feature/new-feature

# 6. Create Pull Request on GitHub
# ... code review ...

# 7. Merge to main
git checkout main
git merge feature/new-feature
git push origin main

# 8. Deploy to production
# ... run deployment checklist ...
```

---

## Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [Sanctum Docs](https://laravel.com/docs/sanctum)
- [API Documentation](./API_DOCUMENTATION.md)
- [Setup Instructions](./SETUP_INSTRUCTIONS.md)
- [Testing Guide](./TESTING_GUIDE.md)
- [Deployment Checklist](./DEPLOYMENT_CHECKLIST.md)

---

**Last Updated:** February 12, 2026
**Version:** 1.0

For detailed information, refer to specific documentation files.
