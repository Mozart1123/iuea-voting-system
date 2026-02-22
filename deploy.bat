@echo off
REM DEPLOYMENT SCRIPT FOR IUEA GUILDVOTE (Windows)
REM Run this after pulling the latest code

echo.
echo ========================================
echo ^! IUEA GuildVote Deployment Script
echo ========================================
echo.

REM 1. Clear caches
echo [1/6] Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo OK: Caches cleared
echo.

REM 2. Install dependencies
echo [2/6] Installing dependencies...
call composer install --no-dev --optimize-autoloader
echo OK: Dependencies installed
echo.

REM 3. Run migrations
echo [3/6] Running database migrations...
call php artisan migrate --force
echo OK: Migrations completed
echo.

REM 4. Seed database (optional)
echo [4/6] Seeding database...
call php artisan db:seed --force
echo OK: Database seeded
echo.

REM 5. Create caches
echo [5/6] Creating application caches...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo OK: Caches created
echo.

REM 6. Final checks
echo [6/6] Running final checks...
call php artisan route:list --path=api > nul
if %ERRORLEVEL%==0 (
    echo OK: All systems operational
) else (
    echo ERROR: Something went wrong
    exit /b 1
)
echo.

echo ========================================
echo ^! Deployment completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Start queue worker: php artisan queue:work --daemon
echo 2. Verify API: curl http://localhost:8000/api/categories
echo 3. Check logs: tail -f storage/logs/laravel.log
echo.
echo Your system is ready to use!
echo.
pause
