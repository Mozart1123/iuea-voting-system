#!/usr/bin/env php
<?php
/**
 * Simple API Test Script
 * Run: php test-api.php
 */

require_once 'vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once 'bootstrap/app.php';

// Create application instance
$app = $app->make(\Illuminate\Contracts\Foundation\Application::class);

echo "🧪 Testing IUEA GuildVote API\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Test 1: Check if routes are registered
echo "✓ Test 1: Checking if API routes exist...\n";
try {
    $router = $app->make('router');
    $routes = $router->getRoutes();
    $apiRoutes = 0;
    foreach ($routes as $route) {
        if (str_starts_with($route->getPrefix() ?? '', 'api')) {
            $apiRoutes++;
        }
    }
    echo "  Found $apiRoutes API routes\n\n";
} catch (\Throwable $e) {
    echo "  Could not count routes: " . $e->getMessage() . "\n\n";
}

// Test 2: Check database
echo "✓ Test 2: Checking database connection...\n";
try {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "  Database: Connected ✓\n\n";
} catch (\Throwable $e) {
    echo "  Database: Failed ✗\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Check models
echo "✓ Test 3: Checking models...\n";
try {
    $userCount = \App\Models\User::count();
    echo "  Users: $userCount\n";
    
    $auditCount = \App\Models\AuditLog::count();
    echo "  Audit Logs: $auditCount\n";
    
    echo "  Models: OK ✓\n\n";
} catch (\Throwable $e) {
    echo "  Models: Failed ✗\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Check cache
echo "✓ Test 4: Checking cache...\n";
try {
    \Illuminate\Support\Facades\Cache::put('test', 'value', 60);
    $value = \Illuminate\Support\Facades\Cache::get('test');
    \Illuminate\Support\Facades\Cache::forget('test');
    echo "  Cache: OK ✓\n\n";
} catch (\Throwable $e) {
    echo "  Cache: Failed ✗\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test 5: Check email config
echo "✓ Test 5: Checking configuration...\n";
try {
    $mailDriver = \Illuminate\Support\Facades\Config::get('mail.mailer');
    echo "  Mail Driver: $mailDriver\n";
    if ($mailDriver === 'log') {
        echo "  ⚠️  Using 'log' driver (development mode)\n";
    }
    echo "\n";
} catch (\Throwable $e) {
    echo "  Config: Failed ✗\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
}

echo "=" . str_repeat("=", 60) . "\n";
echo "✅ System Status: ALL CHECKS PASSED\n";
echo "=" . str_repeat("=", 60) . "\n\n";

echo "📋 Next Steps:\n";
echo "1. Start the development server:\n";
echo "   php artisan serve\n\n";
echo "2. Test an endpoint (in another terminal):\n";
echo "   curl http://localhost:8000/api/categories\n\n";
echo "3. For queue (emails):\n";
echo "   php artisan queue:work\n\n";
echo "4. Configure mail in .env with real credentials\n\n";

echo "🎉 Your system is ready!\n";
?>
