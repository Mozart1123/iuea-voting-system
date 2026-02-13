# IUEA GuildVote - API Documentation

## Authentication

All protected endpoints require authentication using **Laravel Sanctum**.

### Getting an Auth Token

After logging in, the user receives a Bearer token. Include this in API requests:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Testing Authentication

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8000/api/user
```

---

## Public Endpoints

### Get All Active Categories

Returns a list of all active election categories with future deadlines.

**Endpoint:**
```
GET /api/categories
```

**Authentication:** Not required

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Guild President 2025",
      "description": "Elect the next Guild President to lead student affairs.",
      "icon": "fa-user-tie",
      "deadline": "May 05, 2026",
      "deadline_iso": "2026-05-05T17:00:00Z",
      "has_passed_deadline": false,
      "votes_count": 342
    },
    ...
  ]
}
```

---

### Get Category Details

Returns detailed information about a specific category.

**Endpoint:**
```
GET /api/categories/{id}
```

**Parameters:**
- `id` (integer, required) - Category ID

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Guild President 2025",
    "description": "Elect the next Guild President to lead student affairs.",
    "icon": "fa-user-tie",
    "deadline": "May 05, 2026",
    "deadline_iso": "2026-05-05T17:00:00Z",
    "has_passed_deadline": false,
    "votes_count": 342,
    "applications_count": 45
  }
}
```

---

## Student (Authenticated) Endpoints

All endpoints in this section require authentication.

### Get My Applications

Returns all applications submitted by the authenticated user.

**Endpoint:**
```
GET /api/applications
```

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "category_name": "Guild President 2025",
      "status": "pending",
      "statement": "I believe in transparent leadership...",
      "manifesto_url": "https://example.com/manifesto.pdf",
      "submitted_date": "2026-02-01",
      "submitted_date_iso": "2026-02-01T10:30:00Z",
      "reviewed_at": null,
      "reviewer_name": null,
      "can_withdraw": true
    },
    {
      "id": 2,
      "category_id": 2,
      "category_name": "Faculty Representative",
      "status": "approved",
      "statement": "I want to represent our faculty...",
      "manifesto_url": null,
      "submitted_date": "2026-02-03",
      "submitted_date_iso": "2026-02-03T14:15:00Z",
      "reviewed_at": "2026-02-05T09:00:00Z",
      "reviewer_name": "Admin User",
      "can_withdraw": false
    }
  ]
}
```

---

### Submit an Application

Submit an application for an election category.

**Endpoint:**
```
POST /api/applications
```

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "category_id": 1,
  "statement": "I am passionate about student welfare and have experience in leadership roles. I promise to make guild decisions transparent and inclusive.",
  "manifesto_url": "https://example.com/my-manifesto.pdf"
}
```

**Validation Rules:**
- `category_id` (required, integer) - Must exist and not have passed deadline
- `statement` (required, string) - Min 20, Max 500 characters
- `manifesto_url` (optional, string) - Must be valid URL

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Application submitted successfully. Administration will review it shortly.",
  "data": {
    "id": 3,
    "category_id": 1,
    "category_name": "Guild President 2025",
    "status": "pending",
    "submitted_date": "2026-02-12"
  }
}
```

**Error Response (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "You have already applied for this category."
}
```

**Error Response (422 - Deadline Passed):**
```json
{
  "success": false,
  "message": "The application deadline for this category has passed."
}
```

---

### Get Application Details

Get detailed information about a specific application.

**Endpoint:**
```
GET /api/applications/{id}
```

**Parameters:**
- `id` (integer, required) - Application ID

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "category_id": 1,
    "category_name": "Guild President 2025",
    "status": "pending",
    "statement": "I am passionate about...",
    "manifesto_url": "https://example.com/manifesto.pdf",
    "submitted_date": "2026-02-01",
    "submitted_date_iso": "2026-02-01T10:30:00Z",
    "reviewed_at": null,
    "reviewer_name": null,
    "can_withdraw": true
  }
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "You are not authorized to view this application."
}
```

---

### Withdraw Application

Withdraw a pending application. Only applicable to pending applications.

**Endpoint:**
```
DELETE /api/applications/{id}
```

**Parameters:**
- `id` (integer, required) - Application ID

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Application withdrawn successfully."
}
```

**Error Response (422 - Already Reviewed):**
```json
{
  "success": false,
  "message": "Only pending applications can be withdrawn. Your application status is: approved"
}
```

---

### Check if Applied to Category

Check if the user has already applied to a specific category.

**Endpoint:**
```
POST /api/applications/check
```

**Request Body:**
```json
{
  "category_id": 1
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "has_applied": true
}
```

---

### Get Application Statistics

Get aggregated stats about user's applications.

**Endpoint:**
```
GET /api/applications/stats
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "total_applications": 3,
    "pending": 1,
    "approved": 1,
    "rejected": 1,
    "registered": 0
  }
}
```

---

## Admin Endpoints

All endpoints in this section require authentication **AND** admin privileges (`is_admin = true`).

### List All Election Categories (Admin)

**Endpoint:**
```
GET /api/admin/categories
```

**Query Parameters:**
- `per_page` (integer, optional) - Results per page (default: 15)
- `page` (integer, optional) - Page number

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Guild President 2025",
      "description": "...",
      "icon": "fa-user-tie",
      "application_deadline": "2026-05-05T17:00:00Z",
      "is_active": true,
      "created_by": 1,
      "created_at": "2026-02-01T10:00:00Z",
      "updated_at": "2026-02-01T10:00:00Z",
      "creator": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com"
      }
    }
  ],
  "pagination": {
    "total": 6,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1
  }
}
```

---

### Create Election Category (Admin)

**Endpoint:**
```
POST /api/admin/categories
```

**Request Body:**
```json
{
  "name": "Guild Treasurer 2025",
  "description": "Manage Guild finances, budgets, and financial accountability.",
  "icon": "fa-coins",
  "application_deadline": "2026-05-08 17:00",
  "is_active": true
}
```

**Validation Rules:**
- `name` (required, string) - Unique, max 255 chars
- `description` (required, string) - Min 10, Max 1000 chars
- `icon` (required, string) - Max 50 chars
- `application_deadline` (required, datetime) - Format: "YYYY-MM-DD HH:MM", must be in future
- `is_active` (optional, boolean)

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Election category created successfully.",
  "data": {
    "id": 7,
    "name": "Guild Treasurer 2025",
    "description": "...",
    "icon": "fa-coins",
    "application_deadline": "2026-05-08T17:00:00Z",
    "is_active": true,
    "created_by": 1,
    "created_at": "2026-02-12T10:30:00Z",
    "creator": {
      "id": 1,
      "name": "Admin User"
    }
  }
}
```

---

### Update Election Category (Admin)

**Endpoint:**
```
PUT /api/admin/categories/{id}
PATCH /api/admin/categories/{id}
```

**Request Body (only include fields to update):**
```json
{
  "name": "Updated Category Name",
  "description": "Updated description...",
  "is_active": false
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Election category updated successfully.",
  "data": { ... }
}
```

---

### Delete Election Category (Admin)

**Endpoint:**
```
DELETE /api/admin/categories/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Election category deleted successfully."
}
```

---

### Toggle Category Active Status (Admin)

**Endpoint:**
```
PATCH /api/admin/categories/{id}/toggle-active
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Category status updated.",
  "data": {
    "id": 1,
    "is_active": false
  }
}
```

---

### List All Applications (Admin)

List all applications with optional filtering.

**Endpoint:**
```
GET /api/admin/applications
```

**Query Parameters:**
- `status` (string, optional) - Filter by status: "pending", "approved", "rejected", "registered"
- `category_id` (integer, optional) - Filter by category
- `user_id` (integer, optional) - Filter by student
- `sort_by` (string, optional) - Sort field (default: "created_at")
- `sort_order` (string, optional) - "asc" or "desc" (default: "desc")
- `per_page` (integer, optional) - Results per page (default: 15)

**Example Request:**
```
GET /api/admin/applications?status=pending&category_id=1&sort_by=created_at&sort_order=asc
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "category_id": 1,
      "statement": "...",
      "manifesto_url": "...",
      "status": "pending",
      "reviewed_by": null,
      "reviewed_at": null,
      "created_at": "2026-02-01T10:30:00Z",
      "updated_at": "2026-02-01T10:30:00Z",
      "user": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com",
        "student_id": "2023/CS/1245"
      },
      "category": {
        "id": 1,
        "name": "Guild President 2025"
      },
      "reviewer": null
    }
  ],
  "pagination": {
    "total": 45,
    "per_page": 15,
    "current_page": 1,
    "last_page": 3
  }
}
```

---

### Get Application Details (Admin)

**Endpoint:**
```
GET /api/admin/applications/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 2,
    "category_id": 1,
    ... (same structure as above)
  }
}
```

---

### Approve Application (Admin)

Sets application status to "approved".

**Endpoint:**
```
PATCH /api/admin/applications/{id}/approve
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Application approved successfully.",
  "data": {
    "id": 1,
    "status": "approved",
    "reviewed_by": 1,
    "reviewed_at": "2026-02-12T10:30:00Z"
  }
}
```

---

### Reject Application (Admin)

Sets application status to "rejected".

**Endpoint:**
```
PATCH /api/admin/applications/{id}/reject
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Application rejected successfully.",
  "data": {
    "id": 1,
    "status": "rejected",
    "reviewed_by": 1,
    "reviewed_at": "2026-02-12T10:30:00Z"
  }
}
```

---

### Register Application (Admin)

Sets application status to "registered" (only for approved applications).

**Endpoint:**
```
PATCH /api/admin/applications/{id}/register
```

**Prerequisites:**
- Application must have status "approved"

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Application registered successfully.",
  "data": {
    "id": 1,
    "status": "registered"
  }
}
```

**Error Response (422 - Not Approved):**
```json
{
  "success": false,
  "message": "Only approved applications can be registered."
}
```

---

### Get Application Statistics (Admin)

Get aggregated statistics about all applications.

**Endpoint:**
```
GET /api/admin/applications/statistics
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "total": 145,
    "pending": 45,
    "approved": 65,
    "rejected": 20,
    "registered": 15
  }
}
```

---

### Delete Application (Admin)

**Endpoint:**
```
DELETE /api/admin/applications/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Application deleted successfully."
}
```

---

## Error Handling

### Common Error Responses

#### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

#### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized. Admin access required."
}
```

#### 404 Not Found
```json
{
  "message": "Not Found"
}
```

#### 422 Unprocessable Entity (Validation Error)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "statement": [
      "The statement field is required."
    ],
    "category_id": [
      "You have already applied for this category."
    ]
  }
}
```

#### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Failed to process request: [Error details]"
}
```

---

## Status Codes Summary

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Admin access required |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error - Server error |

---

## Example cURL Requests

### Get All Categories
```bash
curl -X GET http://localhost:8000/api/categories
```

### Submit Application
```bash
curl -X POST http://localhost:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "statement": "I am passionate about...",
    "manifesto_url": "https://example.com/manifesto.pdf"
  }'
```

### Approve Application (Admin)
```bash
curl -X PATCH http://localhost:8000/api/admin/applications/1/approve \
  -H "Authorization: Bearer ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

### Create Category (Admin)
```bash
curl -X POST http://localhost:8000/api/admin/categories \
  -H "Authorization: Bearer ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Category",
    "description": "Description of the category",
    "icon": "fa-star",
    "application_deadline": "2026-05-10 17:00",
    "is_active": true
  }'
```

---

**Last Updated:** February 12, 2026
