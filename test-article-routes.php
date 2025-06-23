<?php

/**
 * Test Article Routes
 */

echo "🧪 Testing Article Management System Routes...\n\n";

// Test route registration
echo "1️⃣ Checking Route Registration:\n";

try {
    // Test artisan route:list for articles
    $output = shell_exec('php artisan route:list --path=admin/articles 2>&1');
    
    if ($output && strpos($output, 'articles') !== false) {
        echo "   ✅ Admin article routes are registered\n";
    } else {
        echo "   ❌ Admin article routes not found\n";
        echo "   Output: " . $output . "\n";
    }
    
    // Test API routes
    $apiOutput = shell_exec('php artisan route:list --path=api/v1/articles 2>&1');
    
    if ($apiOutput && strpos($apiOutput, 'articles') !== false) {
        echo "   ✅ API article routes are registered\n";
    } else {
        echo "   ❌ API article routes not found\n";
        echo "   Output: " . $apiOutput . "\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error checking routes: " . $e->getMessage() . "\n";
}

echo "\n";

// Test controller instantiation
echo "2️⃣ Testing Controller:\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    // Test ArticleController can be instantiated
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    echo "   ✅ ArticleController instantiated successfully\n";
    
    // Test API controller
    $apiController = new \App\Http\Controllers\Api\ArticleController();
    echo "   ✅ API ArticleController instantiated successfully\n";
    
} catch (Exception $e) {
    echo "   ❌ Controller error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test models
echo "3️⃣ Testing Models:\n";

try {
    $articlesCount = \App\Models\Article::count();
    echo "   ✅ Article model working - {$articlesCount} articles found\n";
    
    $publishedCount = \App\Models\Article::where('status', 'published')->count();
    echo "   ✅ Published articles: {$publishedCount}\n";
    
} catch (Exception $e) {
    echo "   ❌ Model error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Test Summary:\n";
echo "   The ArticleController middleware error should now be fixed.\n";
echo "   You can start the server with: php artisan serve\n";
echo "   Then access: http://localhost:8000/admin/articles\n";
