# IUEA GuildVote - Voter Registration Implementation Checklist

This checklist guides you through implementing the complete Voter Registration backend and integrating it with the frontend.

---

## Phase 1: Initial Setup ✅

- [ ] **Install Laravel Sanctum**
  ```bash
  composer require laravel/sanctum
  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
  php artisan migrate
  ```

- [ ] **Configure .env**
  - Set correct `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
  - Set `APP_URL` to match your development environment

- [ ] **Copy Backend Files**
  - [ ] All migration files (3 files)
  - [ ] All model files (3 files: ElectionCategory, Application, User)
  - [ ] All controller files (5 files: 2 Admin + 3 Public)
  - [ ] All Form Request files (3 files)
  - [ ] All Policy files (2 files)
  - [ ] Middleware file (1 file: AdminMiddleware)
  - [ ] Seeder file (1 file: ElectionCategorySeeder)

- [ ] **Copy Frontend Files**
  - [ ] JavaScript file: `resources/js/pages/voter-registration.js`
  - [ ] Updated dashboard view: `resources/views/dashboard/index.blade.php`

- [ ] **Copy Documentation**
  - [ ] `SETUP_INSTRUCTIONS.md`
  - [ ] `API_DOCUMENTATION.md`
  - [ ] `MIDDLEWARE_SETUP.md`
  - [ ] `POLICIES_SETUP.md`

---

## Phase 2: Configuration ✅

- [ ] **Register Middleware**
  - [ ] For Laravel 11: Update `bootstrap/app.php` with AdminMiddleware alias
  - [ ] For Laravel 10: Update `app/Http/Kernel.php` with AdminMiddleware in routeMiddleware

- [ ] **Update AuthServiceProvider** (Optional)
  - [ ] Register Policies in `app/Providers/AuthServiceProvider.php`
  - Or rely on auto-discovery if policy naming convention is followed

- [ ] **Update API Routes**
  - [ ] Verify `routes/api.php` is properly set up with all endpoints
  - [ ] Test routes by running: `php artisan route:list --path=api`

---

## Phase 3: Database Setup ✅

- [ ] **Run Migrations**
  ```bash
  php artisan migrate
  ```
  
  This creates:
  - Extended `users` table with new fields
  - `election_categories` table
  - `applications` table

- [ ] **Verify Tables**
  ```bash
  # Check with artisan tinker
  php artisan tinker
  >>> \Schema::getTables()
  ```

- [ ] **Seed Initial Data**
  ```bash
  php artisan db:seed --class=ElectionCategorySeeder
  ```

- [ ] **Create Admin User** (if needed)
  ```bash
  php artisan tinker
  >>> User::create([...admin details...])
  ```

---

## Phase 4: Authentication Setup ✅

- [ ] **Test Authentication with Sanctum**
  - [ ] Login via web/API
  - [ ] Verify token generation
  - [ ] Test protected endpoints with token

- [ ] **Create Test User Tokens**
  - [ ] Create a student account and generate token
  - [ ] Create an admin account and generate token
  - [ ] Store tokens for testing

---

## Phase 5: API Testing ✅

### Public Endpoints
- [ ] `GET /api/categories` - Returns active categories
- [ ] `GET /api/categories/{id}` - Returns category details

### Student Endpoints (Auth Required)
- [ ] `GET /api/applications` - Returns user's applications
- [ ] `POST /api/applications` - Submit new application
- [ ] `GET /api/applications/{id}` - Get application details
- [ ] `DELETE /api/applications/{id}` - Withdraw application

### Admin Endpoints (Admin Auth Required)
- [ ] `GET /api/admin/categories` - List all categories
- [ ] `POST /api/admin/categories` - Create category
- [ ] `PUT /api/admin/categories/{id}` - Update category
- [ ] `DELETE /api/admin/categories/{id}` - Delete category
- [ ] `GET /api/admin/applications` - List all applications
- [ ] `PATCH /api/admin/applications/{id}/approve` - Approve app
- [ ] `PATCH /api/admin/applications/{id}/reject` - Reject app
- [ ] `PATCH /api/admin/applications/{id}/register` - Register app

**Using Postman/cURL:**
```bash
# Test public endpoint
curl http://localhost:8000/api/categories

# Test protected endpoint
curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8000/api/applications
```

---

## Phase 6: Frontend Integration ✅

- [ ] **Include JavaScript File**
  - [ ] Add `<script src="{{ asset('js/pages/voter-registration.js') }}"></script>` to dashboard
  - [ ] Or copy code inline in `<script>` tag

- [ ] **Update Meta Tags** (in dashboard head)
  ```html
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="X-Auth-Token" content="{{ auth()->user()?->currentAccessToken()?->plainTextToken ?? '' }}">
  ```

- [ ] **Test Frontend**
  - [ ] Navigate to Student Dashboard
  - [ ] Click "Voter Registration" sidebar link
  - [ ] Verify categories load from API
  - [ ] Try submitting an application
  - [ ] Verify application appears in "My Applications"

---

## Phase 7: Validation & Error Handling ✅

- [ ] **Test Validation Rules**
  - [ ] Submit application with empty statement
  - [ ] Submit duplicate application for same category
  - [ ] Apply after deadline has passed
  - [ ] Verify proper error messages

- [ ] **Test Authorization**
  - [ ] Try accessing admin endpoint as student
  - [ ] Try accessing another student's application
  - [ ] Try admin operations without token
  - [ ] Verify 403 Forbidden responses

- [ ] **Test Error Scenarios**
  - [ ] Network timeout handling
  - [ ] Invalid JSON responses
  - [ ] Missing required fields
  - [ ] Invalid IDs

---

## Phase 8: User Experience ✅

- [ ] **Loading States**
  - [ ] Show spinner while fetching data
  - [ ] Disable buttons during submission
  - [ ] Update button text: "⏳ Submitting..."

- [ ] **Notifications**
  - [ ] Success messages when application submitted
  - [ ] Error messages for failed operations
  - [ ] Warnings before destructive actions

- [ ] **UI Responsiveness**
  - [ ] Test on desktop (1920px, 1440px)
  - [ ] Test on tablet (768px)
  - [ ] Test on mobile (375px, 414px)
  - [ ] Verify all components are visible and functional

---

## Phase 9: Security Audit ✅

- [ ] **CSRF Protection**
  - [ ] Verify CSRF token is required for mutations
  - [ ] Test with invalid token

- [ ] **Authentication**
  - [ ] Verify all protected routes require auth
  - [ ] Test with expired token
  - [ ] Test with invalid token

- [ ] **Authorization**
  - [ ] Students cannot access admin endpoints
  - [ ] Students can only see their own applications
  - [ ] Admin can see all applications and categories

- [ ] **Input Validation**
  - [ ] Validate all user inputs server-side
  - [ ] Check maximum lengths and formats
  - [ ] Prevent SQL injection through ORM

- [ ] **Data Protection**
  - [ ] Passwords are hashed
  - [ ] Sensitive data is not logged
  - [ ] API responses don't expose security information

---

## Phase 10: Performance Optimization ✅

- [ ] **Database**
  - [ ] Verify indexes on frequently queried columns
  - [ ] Check N+1 query problems using Eager Loading
  - [ ] Indexes exist on: `is_active`, `application_deadline`, `status`, `user_id`, `category_id`

- [ ] **API Response**
  - [ ] Use pagination for large result sets
  - [ ] Load relationships only when needed (with())
  - [ ] Minimize response payload size

- [ ] **Caching** (Optional)
  - [ ] Cache category list (invalidate on changes)
  - [ ] Consider caching admin statistics
  - [ ] Use appropriate cache TTL

---

## Phase 11: Documentation & Handoff ✅

- [ ] **Code Comments**
  - [ ] All public methods have PHPDoc
  - [ ] Complex logic is explained
  - [ ] API responses are documented

- [ ] **README Files**
  - [ ] Review all provided .md files
  - [ ] Print or share documentation with team
  - [ ] Update as needed for your environment

- [ ] **Team Training**
  - [ ] Explain API structure to other developers
  - [ ] Demonstrate frontend-backend communication
  - [ ] Explain authorization flow

---

## Phase 12: Production Preparation ✅

- [ ] **Environment Variables**
  - [ ] Set up `.env.production`
  - [ ] Configure database for production
  - [ ] Set `APP_DEBUG=false`

- [ ] **Database Backups**
  - [ ] Set up automated backups
  - [ ] Test restore procedures

- [ ] **Monitoring**
  - [ ] Set up error tracking (Sentry, etc.)
  - [ ] Monitor API performance
  - [ ] Track failed authentication attempts

- [ ] **Testing**
  - [ ] Run full test suite
  - [ ] Test with production database clone
  - [ ] Load testing with expected user volume

---

## Phase 13: Launch ✅

- [ ] **Final Checks**
  - [ ] All migrations run successfully
  - [ ] All API endpoints working
  - [ ] Frontend loads and functions
  - [ ] Documentation is complete

- [ ] **Deployment**
  - [ ] Deploy code to production
  - [ ] Run migrations on production
  - [ ] Seed initial data
  - [ ] Configure production settings

- [ ] **Post-Launch**
  - [ ] Monitor for errors
  - [ ] Check performance metrics
  - [ ] Gather user feedback
  - [ ] Fix any issues that arise

---

## File Checklist

### Models (✅ 3 files)
- [ ] `app/Models/ElectionCategory.php`
- [ ] `app/Models/Application.php`
- [ ] `app/Models/User.php` (updated)

### Controllers (✅ 5 files)
- [ ] `app/Http/Controllers/Admin/CategoryController.php`
- [ ] `app/Http/Controllers/Admin/ApplicationController.php`
- [ ] `app/Http/Controllers/PublicCategoryController.php`
- [ ] `app/Http/Controllers/ApplicationController.php`

### Form Requests (✅ 3 files)
- [ ] `app/Http/Requests/StoreCategoryRequest.php`
- [ ] `app/Http/Requests/UpdateCategoryRequest.php`
- [ ] `app/Http/Requests/StoreApplicationRequest.php`

### Policies (✅ 2 files)
- [ ] `app/Policies/ElectionCategoryPolicy.php`
- [ ] `app/Policies/ApplicationPolicy.php`

### Middleware (✅ 1 file)
- [ ] `app/Http/Middleware/AdminMiddleware.php`

### Migrations (✅ 3 files)
- [ ] `database/migrations/2026_02_12_140000_add_fields_to_users_table.php`
- [ ] `database/migrations/2026_02_12_140100_create_election_categories_table.php`
- [ ] `database/migrations/2026_02_12_140200_create_applications_table.php`

### Seeders (✅ 1 file)
- [ ] `database/seeders/ElectionCategorySeeder.php`

### JavaScript (✅ 1 file)
- [ ] `resources/js/pages/voter-registration.js`

### Routes (✅ 1 file)
- [ ] `routes/api.php` (created/updated)

### Documentation (✅ 4 files)
- [ ] `SETUP_INSTRUCTIONS.md`
- [ ] `API_DOCUMENTATION.md`
- [ ] `MIDDLEWARE_SETUP.md`
- [ ] `POLICIES_SETUP.md`

### Views (✅ 1 file)
- [ ] `resources/views/dashboard/index.blade.php` (updated)

---

## Quick Start Commands

```bash
# 1. Clean install (development only)
php artisan migrate:fresh --seed

# 2. Run migrations without seeding
php artisan migrate

# 3. Seed categories
php artisan db:seed --class=ElectionCategorySeeder

# 4. Create admin user
php artisan tinker

# 5. List all API routes
php artisan route:list --path=api

# 6. Clear caches if needed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## Getting Help

If you encounter issues:

1. **Check logs**: `storage/logs/laravel.log`
2. **Review documentation**: All .md files in root
3. **Test endpoint**: Use cURL or Postman
4. **Debug mode**: Set `APP_DEBUG=true` in .env
5. **Tinker**: Use `php artisan tinker` to test queries

---

**Estimated Time to Complete:** 4-6 hours for complete setup and testing

**Last Updated:** February 12, 2026
