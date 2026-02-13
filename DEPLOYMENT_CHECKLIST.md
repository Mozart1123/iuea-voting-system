# Production Deployment Checklist

This checklist ensures your IUEA GuildVote system is properly configured and ready for production deployment.

## Pre-Deployment

### Environment Configuration

- [ ] Create `.env.production` file from `.env.example`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`: `php artisan key:generate`
- [ ] Set secure `APP_URL` (e.g., https://iuea-vote.example.com)
- [ ] Configure database credentials (use strong passwords)
- [ ] Set `SESSION_SECURE_COOKIES=true` (HTTPS only)
- [ ] Set `SESSION_HTTP_ONLY=true` (prevent JavaScript access)
- [ ] Enable HTTPS/SSL certificate

### Dependencies & Caching

- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Remove dev dependencies: Debugbar, Tinker, etc.
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan event:cache` (if using events)

### Database Setup

- [ ] Verify database connection works
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed initial data: `php artisan db:seed --force`
- [ ] Verify all tables created: `php artisan tinker` → `\DB::select("SHOW TABLES");`
- [ ] Check foreign key constraints are in place
- [ ] Create database backups before deployment

### File Permissions

```bash
# Set correct Laravel directory permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Set ownership (if using specific user)
chown -R www-data:www-data .
```

- [ ] Storage directory writable
- [ ] Bootstrap cache directory writable
- [ ] Logs directory writable
- [ ] Public directory accessible from web

### Security Configuration

**CORS Settings:**

Update `config/sanctum.php`:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

Set in `.env.production`:

```env
SANCTUM_STATEFUL_DOMAINS=iuea-vote.example.com
```

- [ ] CORS domains configured correctly
- [ ] Session domain matches APP_URL
- [ ] Sanctum token prefix set (optional): `SANCTUM_TOKEN_PREFIX=`

**Security Headers:**

Add to `app/Http/Middleware/` or use web server:

```
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
```

- [ ] Security headers configured
- [ ] HTTPS enforced
- [ ] HSTS enabled

### API Security

- [ ] Rate limiting configured in `routes/api.php`:

```php
Route::middleware(['api', 'throttle:60,1'])->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});
```

- [ ] CSRF protection enabled for web routes
- [ ] API token expiration set (if desired)
- [ ] Admin endpoints protected with middleware
- [ ] Validation rules enforced on all inputs
- [ ] SQL injection prevention (using Eloquent/prepared statements)

### Logging & Monitoring

- [ ] Logging configured: `.env` → `LOG_CHANNEL=daily`
- [ ] Log rotation setup: `config/logging.php`
- [ ] Error tracking configured (Sentry, Rollbar, etc.)
- [ ] Monitoring alerts configured
- [ ] Database query logging disabled in production
- [ ] Debugbar disabled in production

## Deployment Day

### Pre-Deployment Checklist

```bash
# 1. Create full backup
mysqldump -u user -p voting_db > voting_backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Verify all code is committed
git status
git log --oneline -5

# 3. Run tests
php artisan test
```

- [ ] Full database backup created
- [ ] All tests passing
- [ ] No uncommitted changes

### Deployment Steps

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Update environment
cp .env.production .env

# 4. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Database migrations (if new migrations exist)
php artisan migrate --force

# 6. Clear old caches
php artisan cache:clear
php artisan queue:restart

# 7. Restart application (if using PM2/Supervisor)
pm2 restart laravel-app
# or
sudo supervisorctl restart laravel-workers:*

# 8. Verify deployment
curl -I https://iuea-vote.example.com
```

- [ ] Code deployed successfully
- [ ] Database migrated
- [ ] Caches cleared
- [ ] Application restarted
- [ ] Health check passed

### Post-Deployment Verification

#### 1. API Endpoint Testing

```bash
# Public endpoint
curl https://iuea-vote.example.com/api/categories

# Protected endpoint (with token)
curl -H "Authorization: Bearer TOKEN" \
     https://iuea-vote.example.com/api/applications
```

- [ ] Public API endpoints responding
- [ ] Authentication working
- [ ] Database queries successful

#### 2. Frontend Testing

- [ ] Dashboard loads without errors
- [ ] Voter Registration page displays
- [ ] Categories load correctly
- [ ] Form submission works
- [ ] API integration functions properly

#### 3. Email Configuration (Optional)

If implementing email notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@iuea-vote.example.com
MAIL_FROM_NAME="IUEA GuildVote"
```

Test email:

```php
// In tinker
Mail::to('test@example.com')->send(new TestMail());
```

- [ ] Email configuration verified
- [ ] Test email sent successfully

#### 4. Performance Check

```bash
# Check load time
time curl https://iuea-vote.example.com/api/categories

# Check response time is < 500ms
```

- [ ] Response times acceptable (< 500ms)
- [ ] No timeout errors
- [ ] Database queries optimized

#### 5. Security Verification

```bash
# Check headers
curl -I https://iuea-vote.example.com

# Verify redirect HTTP → HTTPS
curl -I http://iuea-vote.example.com
```

- [ ] HTTPS enforced
- [ ] Security headers present
- [ ] SSL certificate valid
- [ ] No insecure content warnings

### Monitoring Setup

#### 1. Application Monitoring

```bash
# Install monitoring tools
composer require predis/predis  # For caching
npm install pm2 -g              # For process management
```

Configure PM2 (if applicable):

```bash
# ecosystem.config.js
module.exports = {
  apps: [{
    name: 'laravel-app',
    script: 'artisan',
    args: 'serve',
    instances: 1,
    max_memory_restart: '1G',
    watch: false,
    env: {
      APP_ENV: 'production'
    }
  }]
};

pm2 start ecosystem.config.js
```

- [ ] Process manager configured (PM2/Supervisor)
- [ ] Auto-restart enabled
- [ ] Memory limits set

#### 2. Database Monitoring

```bash
# Check slow queries (MySQL)
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

# Check active connections
SHOW PROCESSLIST;

# Check database size
SELECT sum(data_length + index_length) / 1024 / 1024 AS size_mb 
FROM information_schema.tables 
WHERE table_schema = 'voting_db';
```

- [ ] Slow query logging enabled
- [ ] Query monitoring configured
- [ ] Database size monitored

#### 3. Log Monitoring

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Or use log aggregation service
# - New Relic
# - Datadog
# - LogRocket
```

- [ ] Log aggregation configured
- [ ] Error notifications enabled

### Backup Strategy

#### Daily Backup

```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="/backups/voting"
DB_NAME="voting_db"
DB_USER="root"
DB_PASS="password"
DATE=$(date +%Y%m%d_%H%M%S)

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# File backup (optional)
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/voting

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

Schedule in crontab:

```bash
crontab -e

# Run daily at 2 AM
0 2 * * * /path/to/backup.sh
```

- [ ] Automated backups configured
- [ ] Backup retention policy set
- [ ] Backup restoration tested

### SSL/TLS Certificate

Using Let's Encrypt with Certbot:

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# Get certificate
sudo certbot certonly --nginx -d iuea-vote.example.com

# Auto-renewal (runs daily)
sudo certbot renew --quiet

# Verify renewal
sudo cert-annual.sh
```

- [ ] SSL certificate installed
- [ ] Certificate auto-renewal enabled
- [ ] Certificate expiration monitored

## Ongoing Maintenance

### Weekly Tasks

- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Verify backups were created successfully
- [ ] Monitor disk usage: `df -h`
- [ ] Check database size growth
- [ ] Verify all services running: `ps aux | grep laravel`

### Monthly Tasks

- [ ] Update dependencies: `composer update`
- [ ] Security scan: `composer audit`
- [ ] Database optimization: `OPTIMIZE TABLE applications, election_categories, users;`
- [ ] Clean old logs: `php artisan logs:clear`
- [ ] Review access logs for suspicious activity
- [ ] Test disaster recovery procedure

### Quarterly Tasks

- [ ] Full security audit
- [ ] Performance benchmarking
- [ ] Load testing
- [ ] Database migration testing
- [ ] Backup restoration drill

### Annual Tasks

- [ ] Infrastructure review
- [ ] Capacity planning
- [ ] Security assessment
- [ ] Compliance audit
- [ ] Cost optimization review

## Troubleshooting Production Issues

### Application Won't Start

```bash
# Check errors
php artisan serve
# Look for specific error messages

# Verify permissions
ls -la storage bootstrap/cache

# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

### High Response Times

```bash
# Check slow queries
tail storage/logs/laravel.log

# Verify database indexes
SHOW INDEXES FROM applications;
SHOW INDEXES FROM election_categories;

# Check server resources
top
free -h
```

### Memory Issues

```bash
# Check memory usage
free -h
vmstat 1 5

# Optimize Laravel
php artisan optimize

# Increase PHP memory limit (php.ini)
memory_limit = 512M
```

### Database Connection Errors

```bash
# Verify connection details
.env file → DB_HOST, DB_PORT, DB_DATABASE

# Test connection
mysql -h 127.0.0.1 -u user -p -e "SELECT 1"

# Check max connections
SHOW VARIABLES LIKE 'max_connections';
SHOW PROCESSLIST;
```

### API Timeouts

```bash
# Increase timeout in nginx
proxy_connect_timeout 60s;
proxy_send_timeout 30s;
proxy_read_timeout 30s;

# Or in PHP
set_time_limit(60);
```

## Server Configuration Examples

### Nginx Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name iuea-vote.example.com;

    ssl_certificate /etc/letsencrypt/live/iuea-vote.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/iuea-vote.example.com/privkey.pem;

    root /var/www/voting/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name iuea-vote.example.com;
    return 301 https://$server_name$request_uri;
}
```

### Apache Configuration

```apache
<VirtualHost *:443>
    ServerName iuea-vote.example.com
    DocumentRoot /var/www/voting/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/iuea-vote.example.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/iuea-vote.example.com/privkey.pem

    # Enable mod_rewrite
    <Directory /var/www/voting/public>
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>

    # Security headers
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName iuea-vote.example.com
    Redirect / https://iuea-vote.example.com/
</VirtualHost>
```

## Emergency Procedures

### Database is Corrupted

```bash
# 1. Restore from backup
mysql voting_db < /backups/voting/db_20260212_120000.sql

# 2. Verify data integrity
php artisan tinker
App\Models\Application::count();
App\Models\ElectionCategory::count();

# 3. Check for orphaned records
DB::select("
    SELECT * FROM applications 
    WHERE user_id NOT IN (SELECT id FROM users)
");
```

### Application Crashed

```bash
# 1. Check logs
tail -f storage/logs/laravel.log

# 2. Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 3. Restart application
php artisan serve  # For development
# Or restart process manager (PM2/Supervisor)

# 4. If database issue:
php artisan migrate:refresh --force  # CAREFUL: Deletes data!
```

### Security Breach

```bash
# 1. Disable application immediately
// In .env: APP_DEBUG=false
// Redirect traffic or take server offline

# 2. Check logs for suspicious activity
grep "error\|failed" storage/logs/laravel.log

# 3. Revoke all user tokens
php artisan tinker
DB::table('personal_access_tokens')->delete();

# 4. Change all credentials
# Database password
# Application keys
# API tokens

# 5. Restore from clean backup
# If compromise is confirmed

# 6. Notify users of incident
```

---

**Last Updated:** February 12, 2026
**Version:** 1.0
**Critical:** Always maintain updated backups before making any changes.
