# 🧪 Testing Guide - IUEA GuildVote API

## Quick Test Commands

### 1. Get Public Categories (No Auth Required)
```bash
curl -X GET http://localhost:8000/api/categories
```

Expected response:
```json
{
  "success": true,
  "data": [...]
}
```

### 2. Register a New User
```bash
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Student",
    "email": "test@iuea.ac.ug",
    "password": "password",
    "password_confirmation": "password",
    "student_id": "ST001",
    "faculty": "Engineering"
  }'
```

### 3. Get Auth Token via Login
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@iuea.ac.ug",
    "password": "password"
  }'
```

Copy the token from response.

### 4. Test Authenticated Endpoint
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 5. Submit an Application
```bash
curl -X POST http://localhost:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "statement": "I am running for Guild President because...",
    "manifesto_url": "https://example.com/manifesto.pdf"
  }'
```

### 6. View Your Applications
```bash
curl -X GET http://localhost:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 7. Cast a Vote
```bash
curl -X POST http://localhost:8000/api/votes \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "candidate_id": 1,
    "category_id": 1
  }'
```

### 8. View Voting History
```bash
curl -X GET http://localhost:8000/api/votes/history \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 9. View Election Results
```bash
curl -X GET http://localhost:8000/api/categories/1/results \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 10. Admin: List All Applications
```bash
curl -X GET http://localhost:8000/api/admin/applications \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

### 11. Admin: Approve an Application
```bash
curl -X POST http://localhost:8000/api/admin/applications/1/approve \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

### 12. Admin: Export Results as CSV
```bash
curl -X GET http://localhost:8000/api/admin/export/categories/1/results \
  -H "Authorization: Bearer ADMIN_TOKEN" \
  > results.csv
```

---

## 📊 Testing with Postman

1. **Import Collection:**
   - Create new collection named "IUEA GuildVote API"

2. **Create These Folders:**
   - Public
   - Authentication
   - Applications
   - Voting
   - Admin
   - Exports

3. **Add Requests:**

### Public Folder
- GET http://localhost:8000/api/categories
- GET http://localhost:8000/api/categories/1

### Authentication Folder
- POST http://localhost:8000/register
- POST http://localhost:8000/login
- GET http://localhost:8000/api/user (with auth)

### Applications Folder
- GET http://localhost:8000/api/applications (with auth)
- POST http://localhost:8000/api/applications (with auth)
- GET http://localhost:8000/api/applications/check (with auth)
- GET http://localhost:8000/api/applications/stats (with auth)

### Voting Folder
- POST http://localhost:8000/api/votes (with auth)
- GET http://localhost:8000/api/votes/history (with auth)
- GET http://localhost:8000/api/categories/1/results (with auth)

### Admin Folder
- GET http://localhost:8000/api/admin/applications (admin auth)
- POST http://localhost:8000/api/admin/applications/1/approve (admin auth)
- GET http://localhost:8000/api/admin/export/categories/1/results (admin auth)

---

## 🐛 Debugging

### Enable Debug Mode
```bash
# In .env
APP_DEBUG=true
```

### View Logs
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Or on Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

### Check Audit Logs
```bash
php artisan tinker
>>> \App\Models\AuditLog::latest()->first();
```

### Check Database
```bash
php artisan tinker
>>> \App\Models\Application::all();
>>> \App\Models\Vote::all();
>>> \App\Models\AuditLog::all();
```

---

## ✅ Test Checklist

- [ ] Public categories endpoint works
- [ ] User registration completes
- [ ] User login returns token
- [ ] Authenticated endpoints accessible with token
- [ ] Application submission creates entry
- [ ] Application appears in user's list
- [ ] Admin can list applications
- [ ] Admin can approve/reject applications
- [ ] Vote submission works
- [ ] Double voting prevented (rate limiting works)
- [ ] Voting history shows votes
- [ ] Results calculated correctly
- [ ] CSV exports work
- [ ] Audit logs created for actions
- [ ] Emails queued (check queue:work if using async)

---

## 🚀 Start Development Server

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Queue worker (for emails)
php artisan queue:work

# Terminal 3 - Optional: watch for file changes
php artisan tinker
```

---

## 📧 Email Testing

### Option 1: Mailtrap (Recommended)
1. Sign up at https://mailtrap.io
2. Get SMTP credentials
3. Add to `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Option 2: Mailgun
1. Sign up at https://www.mailgun.com
2. Configure in `.env`:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your_domain
MAILGUN_SECRET=your_secret
```

### Option 3: SendGrid
1. Sign up at https://sendgrid.com
2. Configure in `.env`:
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your_api_key
```

### Test Email Sending
```bash
php artisan tinker
>>> \Illuminate\Support\Facades\Mail::to('test@example.com')->send(new \App\Mail\ApplicationSubmittedMail(\App\Models\Application::first()));
```

---

## 🔍 Rate Limiting Test

Try voting twice rapidly:
```bash
# First vote - should succeed
curl -X POST http://localhost:8000/api/votes \
  -H "Authorization: Bearer TOKEN" \
  -d '{"candidate_id":1,"category_id":1}'

# Second vote within 1 minute - should fail with 429
curl -X POST http://localhost:8000/api/votes \
  -H "Authorization: Bearer TOKEN" \
  -d '{"candidate_id":2,"category_id":1}'
```

Expected response for rate-limited request:
```json
{
  "success": false,
  "message": "You are voting too quickly. Please try again in X seconds."
}
```

---

## 📋 Common Issues & Solutions

### 1. **CORS Error**
**Problem:** Requests from frontend blocked  
**Solution:** Make sure frontend URL is trusted or configure CORS middleware

### 2. **Email Not Sending**
**Problem:** Emails not in Mailtrap inbox  
**Solution:** 
- Check queue is running: `php artisan queue:work`
- Check `.env` mail config
- Check `storage/logs/laravel.log` for errors

### 3. **Token Invalid**
**Problem:** 401 Unauthorized on protected routes  
**Solution:**
- Make sure you logged in and copied the full token
- Check token isn't expired
- Make sure `Authorization: Bearer TOKEN` header is correct

### 4. **Database Connection Error**
**Problem:** Cannot connect to database  
**Solution:**
- Check `.env` database settings
- Make sure MySQL is running
- Run `php artisan migrate` to setup tables

### 5. **Route Not Found**
**Problem:** 404 on API endpoints  
**Solution:**
- Run `php artisan route:cache --clear`
- Check `routes/api.php` exists
- Run `php artisan route:list --path=api` to debug

---

**Happy Testing! 🎉**
