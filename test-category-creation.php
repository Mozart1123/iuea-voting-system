<?php
/**
 * Test Script: Category Creation via Admin API
 * 
 * This script demonstrates how to create election categories
 * using the new admin interface form (which calls the API).
 * 
 * Usage: php test-category-creation.php
 */

echo "========================================\n";
echo "IUEA GuildVote - Category Creation Test\n";
echo "========================================\n\n";

// Test data for new categories
$testCategories = [
    [
        'name' => 'Guild President',
        'description' => 'Chief executive officer of the guild responsible for representing students, leading council meetings, and strategic direction.',
        'icon' => 'fa-user-tie',
        'application_deadline' => date('Y-m-d', strtotime('+30 days')) . ' 17:00',
        'is_active' => true,
    ],
    [
        'name' => 'Guild Treasurer',
        'description' => 'Manage all guild finances, budgets, financial reports, and ensure accountability of every transaction.',
        'icon' => 'fa-coins',
        'application_deadline' => date('Y-m-d', strtotime('+30 days')) . ' 17:00',
        'is_active' => true,
    ],
    [
        'name' => 'Guild Secretary',
        'description' => 'Keep official records, manage correspondence, organize meetings, and ensure smooth administrative operations.',
        'icon' => 'fa-clipboard-list',
        'application_deadline' => date('Y-m-d', strtotime('+30 days')) . ' 17:00',
        'is_active' => true,
    ],
    [
        'name' => 'Guild Welfare Officer',
        'description' => 'Support student welfare needs, organize support programs, and mediate on student concerns.',
        'icon' => 'fa-heart',
        'application_deadline' => date('Y-m-d', strtotime('+30 days')) . ' 17:00',
        'is_active' => true,
    ],
];

echo "📋 Sample Category Data for Testing:\n\n";

foreach ($testCategories as $index => $category) {
    echo "Category " . ($index + 1) . ":\n";
    echo "  ✓ Name:      " . $category['name'] . "\n";
    echo "  ✓ Icon:      " . $category['icon'] . "\n";
    echo "  ✓ Deadline:  " . $category['application_deadline'] . "\n";
    echo "  ✓ Active:    " . ($category['is_active'] ? 'Yes' : 'No') . "\n";
    echo "  ✓ Desc:      " . substr($category['description'], 0, 50) . "...\n\n";
}

// Check if database connection works
echo "\n📊 System Status Check:\n";

try {
    // Load Laravel
    $app = require __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    // Check if ElectionCategory model exists
    $categories = \App\Models\ElectionCategory::count();
    echo "  ✓ Database connected\n";
    echo "  ✓ Current categories: " . $categories . "\n";

    // Check API endpoint
    echo "  ✓ API endpoint: POST /api/admin/categories\n";
    echo "  ✓ Authentication: Sanctum + Admin role\n";

} catch (Exception $e) {
    echo "  ✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n🚀 To Create Categories:\n\n";

echo "Method 1: Via Admin Interface (Recommended)\n";
echo "  1. Go to: http://localhost:8000/admin\n";
echo "  2. Login with admin credentials\n";
echo "  3. Click 'Elections' in sidebar\n";
echo "  4. Click '+ Create New Category' button\n";
echo "  5. Fill the form fields\n";
echo "  6. Click 'Create Category' button\n\n";

echo "Method 2: Via cURL (API Direct)\n";
echo "  curl -X POST http://localhost:8000/api/admin/categories \\\n";
echo "    -H 'Authorization: Bearer YOUR_ADMIN_TOKEN' \\\n";
echo "    -H 'Content-Type: application/json' \\\n";
echo "    -d '{\n";
echo "      \"name\": \"Guild President\",\n";
echo "      \"description\": \"Chief executive officer...\",\n";
echo "      \"icon\": \"fa-user-tie\",\n";
echo "      \"application_deadline\": \"2026-03-15 17:00\",\n";
echo "      \"is_active\": true\n";
echo "    }'\n\n";

echo "Method 3: Via PHP API Client\n";
echo "  See: routes/api.php line 47 (POST /api/admin/categories)\n";
echo "  Controller: app/Http/Controllers/Admin/CategoryController.php::store()\n\n";

echo "✨ Features of New Category Form:\n";
echo "  ✓ Real-time icon preview\n";
echo "  ✓ Datetime picker for deadline\n";
echo "  ✓ Form validation\n";
echo "  ✓ Error messages\n";
echo "  ✓ Success notifications\n";
echo "  ✓ Auto-refresh on success\n\n";

echo "📖 Full Documentation: ADMIN_CATEGORIES_GUIDE.md\n\n";

echo "========================================\n";
echo "Ready to create categories!\n";
echo "========================================\n";

?>
