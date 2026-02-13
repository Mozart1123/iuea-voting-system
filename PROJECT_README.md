# IUEA GuildVote - Voter Registration System

Complete backend implementation of the Voter Registration system for IUEA (International University of East Africa).

## 🎯 Project Overview

IUEA GuildVote is a comprehensive voting management system that allows:

- **Administrators**: Create and manage election categories, review student applications, and approve/register candidates
- **Students**: Apply to election categories they're interested in, manage their applications, and track approval status
- **Public**: View active election categories and key information

## ✨ Features

### Core Features

- ✅ **Election Categories Management** - Create, update, delete positions/categories
- ✅ **Student Applications** - Students can apply to categories with detailed statements
- ✅ **Application Workflow** - Pending → Approved → Registered workflow
- ✅ **Admin Review System** - Approve, reject, or register applications
- ✅ **Role-Based Access Control** - Admin and student access levels
- ✅ **API-Driven Architecture** - RESTful JSON API with Sanctum authentication
- ✅ **Frontend Integration** - Interactive UI with real-time updates

### Technical Features

- Laravel 10/11 REST API
- Sanctum token-based authentication
- Form Request validation with custom rules
- Policy-based authorization
- Eloquent ORM with relationships
- Database migrations for versioning
- Comprehensive error handling
- PaginatedQuery results

## 📁 Project Structure

```
voting/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── CategoryController.php
│   │   │   │   └── ApplicationController.php
│   │   │   ├── ApplicationController.php
│   │   │   └── PublicCategoryController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── StoreCategoryRequest.php
│   │       ├── UpdateCategoryRequest.php
│   │       └── StoreApplicationRequest.php
│   ├── Models/
│   │   ├── ElectionCategory.php
│   │   ├── Application.php
│   │   └── User.php
│   └── Policies/
│       ├── ElectionCategoryPolicy.php
│       └── ApplicationPolicy.php
├── database/
│   ├── migrations/
│   │   ├── 2026_02_12_140000_add_fields_to_users_table.php
│   │   ├── 2026_02_12_140100_create_election_categories_table.php
│   │   └── 2026_02_12_140200_create_applications_table.php
│   └── seeders/
│       └── ElectionCategorySeeder.php
├── resources/
│   ├── js/
│   │   └── pages/
│   │       └── voter-registration.js
│   └── views/
│       └── dashboard/
├── routes/
│   └── api.php
├── Documentation/
│   ├── API_DOCUMENTATION.md
│   ├── SETUP_INSTRUCTIONS.md
│   ├── IMPLEMENTATION_CHECKLIST.md
│   ├── MIDDLEWARE_SETUP.md
│   ├── POLICIES_SETUP.md
│   ├── SANCTUM_CONFIGURATION.md
│   ├── TESTING_GUIDE.md
│   ├── DEPLOYMENT_CHECKLIST.md
│   ├── QUICK_REFERENCE.md
│   └── BACKEND_IMPLEMENTATION_SUMMARY.md
└── ...other Laravel files...
```

## 🚀 Quick Start

### Prerequisites

- PHP 8.0+
- Composer
- MySQL/PostgreSQL
- Git
- Node.js (for asset compilation)

### Installation

1. **Clone Repository**
```bash
cd c:/xampp/htdocs/voting
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database** (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Seed Sample Data**
```bash
php artisan db:seed --class=ElectionCategorySeeder
```

7. **Create Admin User** (Tinker)
```bash
php artisan tinker

$admin = User::create([
    'name' => 'Admin User',
    'email' => 'admin@iuea.ac.ug',
    'password' => Hash::make('password123'),
    'student_id' => 'ADMIN001',
    'is_admin' => true,
]);

$token = $admin->createToken('api-token')->plainTextToken;
echo $token;
exit;
```

8. **Start Development Server**
```bash
php artisan serve
```

Access at: `http://localhost:8000`

## 📚 API Endpoints

### Public Endpoints

```
GET /api/categories           # List all active categories
GET /api/categories/{id}      # Get category details
```

### Student Endpoints (Requires Authentication)

```
POST   /api/applications               # Submit application
GET    /api/applications               # Get my applications
GET    /api/applications/{id}          # Get application details
DELETE /api/applications/{id}          # Withdraw application
```

### Admin Endpoints (Requires Authentication + Admin Role)

```
# Categories
GET    /api/admin/categories                    # List all categories
POST   /api/admin/categories                    # Create category
PUT    /api/admin/categories/{id}               # Update category
DELETE /api/admin/categories/{id}               # Delete category
PATCH  /api/admin/categories/{id}/toggle-active # Toggle active status

# Applications
GET    /api/admin/applications                  # List applications
PATCH  /api/admin/applications/{id}/approve     # Approve application
PATCH  /api/admin/applications/{id}/reject      # Reject application
PATCH  /api/admin/applications/{id}/register    # Register application
```

## 🔐 Authentication

### Using Sanctum Tokens

1. **Get Token**
```php
$user = User::find(1);
$token = $user->createToken('api-token')->plainTextToken;
```

2. **Use in Requests**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8000/api/applications
```

3. **JavaScript**
```javascript
fetch('/api/applications', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    }
})
```

## 📝 Data Models

### User Model
```php
- id: integer
- name: string
- email: string (unique)
- student_id: string (unique)
- faculty: string (optional)
- year_of_study: integer (optional)
- is_admin: boolean (default: false)
- Relationships: applications, createdCategories, reviewedApplications
```

### ElectionCategory Model
```php
- id: integer
- name: string (unique)
- description: text
- icon: string (Font Awesome icon)
- application_deadline: datetime
- is_active: boolean
- created_by: integer (FK to User)
- Relationships: applications, createdBy
```

### Application Model
```php
- id: integer
- user_id: integer (FK)
- category_id: integer (FK)
- statement: text
- manifesto_url: string (optional)
- status: enum (pending, approved, rejected, registered)
- reviewed_by: integer (FK to User, nullable)
- reviewed_at: datetime (nullable)
- Relationships: user, category, reviewer
```

## 🧪 Testing

### Run Tests

```bash
# All tests
php artisan test

# Specific test
php artisan test tests/Feature/ApplicationApiTest.php

# With coverage
php artisan test --coverage
```

### Manual Testing with cURL

See [TESTING_GUIDE.md](TESTING_GUIDE.md) for comprehensive testing instructions.

## 📖 Documentation Files

| File | Purpose |
|------|---------|
| [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Quick cheat sheet for common tasks |
| [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md) | Step-by-step setup guide |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Complete API reference |
| [TESTING_GUIDE.md](TESTING_GUIDE.md) | Testing procedures and examples |
| [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) | Production deployment guide |
| [SANCTUM_CONFIGURATION.md](SANCTUM_CONFIGURATION.md) | Authentication setup |
| [MIDDLEWARE_SETUP.md](MIDDLEWARE_SETUP.md) | Middleware configuration |
| [POLICIES_SETUP.md](POLICIES_SETUP.md) | Authorization policies |
| [IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md) | Implementation phases |
| [BACKEND_IMPLEMENTATION_SUMMARY.md](BACKEND_IMPLEMENTATION_SUMMARY.md) | Project summary |

## 🔧 Development Commands

### Database

```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Reset database
php artisan db:seed              # Run seeders
php artisan tinker              # Interactive shell
```

### Cache

```bash
php artisan config:cache        # Cache configuration
php artisan route:cache         # Cache routes
php artisan cache:clear         # Clear cache
```

### Code Generation

```bash
php artisan make:model Name -m  # Model with migration
php artisan make:controller Name # Controller
php artisan make:request Name    # Form Request
php artisan make:policy Name     # Policy
php artisan make:migration Name  # Migration
```

## 📋 Workflow

### For Students

1. Navigate to Student Dashboard
2. Click "Voter Registration"
3. View available categories
4. Click "Apply Now" on desired category
5. Fill in application details
6. Submit application
7. Track status in "My Applications"
8. Can withdraw if still pending

### For Administrators

1. Login as admin
2. Access Admin Panel
3. **Categories**: Create, update, delete, toggle status
4. **Applications**: Review pending applications
5. **Actions**: 
   - Approve for consideration
   - Reject with feedback
   - Register approved candidates

## 🛡️ Security

- Sanctum token-based authentication
- Role-based access control (Admin/Student)
- Policy-based authorization
- Form Request validation
- SQL injection prevention (Eloquent)
- CSRF protection
- Input validation and sanitization
- Bcrypt password hashing

## 🚢 Deployment

See [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) for:
- Pre-deployment configuration
- Database setup
- Security hardening
- Server configuration (Nginx/Apache)
- SSL/TLS setup
- Monitoring and logging
- Backup strategies

## 🐛 Troubleshooting

### Common Issues

**"Unauthenticated" Error**
- Verify token is valid
- Check Authorization header format: `Bearer TOKEN`
- Ensure user still exists in database

**CORS Errors**
- Update `.env`: `SANCTUM_STATEFUL_DOMAINS`
- Clear config cache: `php artisan config:cache`

**Database Connection Failed**
- Verify database exists
- Check credentials in `.env`
- Test with: `php artisan tinker` → `DB::connection()->getPdo();`

**Permission Denied**
- Check user `is_admin` flag
- Verify policies are registered
- Check middleware is active

See [TESTING_GUIDE.md](TESTING_GUIDE.md#troubleshooting) for more solutions.

## 📞 Support

For detailed information on specific topics:

- **API Usage**: See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- **Quick Commands**: See [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
- **Setup Issues**: See [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md)
- **Testing**: See [TESTING_GUIDE.md](TESTING_GUIDE.md)
- **Deployment**: See [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)

## 🤝 Code Structure Best Practices

### Controllers
- Handle HTTP requests/responses
- Delegate business logic to models
- Return JSON responses
- Implement error handling

### Models
- Define database relationships
- Include helper methods
- Implement data transformations
- Use accessors/mutators as needed

### Requests
- Validate all inputs
- Define authorization rules
- Custom error messages
- Unique constraints

### Policies
- Authorization logic
- View/Create/Update/Delete permissions
- Owner-based checks

### Middleware
- Request preprocessing
- Authentication verification
- Authorization checks
- Logging and monitoring

## 📊 Performance Considerations

- ✅ Eager loading of relationships (with())
- ✅ Pagination of large result sets
- ✅ Database indexes on frequently queried columns
- ✅ Query optimization with select()
- ✅ Cache configuration for static data
- ✅ Rate limiting on API endpoints

## 🔄 Database Relationships

```
User
├── hasMany → Application (as student)
├── hasMany → Application (as reviewer, reviewed_by)
└── hasMany → ElectionCategory (as creator, created_by)

ElectionCategory
├── hasMany → Application
└── belongsTo → User (created_by)

Application
├── belongsTo → User (student)
├── belongsTo → User (reviewer)
└── belongsTo → ElectionCategory
```

## 🎓 Learning Resources

- [Laravel Official Documentation](https://laravel.com/docs)
- [Sanctum Authentication Guide](https://laravel.com/docs/sanctum)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [REST API Best Practices](https://restfulapi.net/)

## 📝 Version History

- **v1.0** (2026-02-12): Initial release with complete backend implementation

## 📄 License

This project is proprietary to IUEA. All rights reserved.

## 👥 Team

Developed as part of IUEA GuildVote initiative.

---

## 🎯 Next Steps

1. Complete installation and setup (see [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md))
2. Test API endpoints (see [TESTING_GUIDE.md](TESTING_GUIDE.md))
3. Review and implement improvements
4. Deploy to production (see [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md))
5. Monitor and maintain system

## 📞 Support Contact

For technical support, refer to documentation files or contact development team.

---

**Last Updated**: February 12, 2026  
**Version**: 1.0  
**Status**: Production Ready ✅
