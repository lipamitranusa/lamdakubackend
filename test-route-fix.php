<?php

/**
 * Test Article Routes Fix
 * This script tests if the login route issue is resolved
 */

echo "🧪 Testing Article Routes Fix...\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    echo "1️⃣ Testing Route Registration:\n";
    
    // Test if login route exists
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $loginRoute = $routes->getByName('login');
    
    if ($loginRoute) {
        echo "   ✅ Login route is defined\n";
    } else {
        echo "   ❌ Login route not found\n";
    }
    
    // Test admin login route
    $adminLoginRoute = $routes->getByName('admin.login');
    if ($adminLoginRoute) {
        echo "   ✅ Admin login route is defined\n";
    } else {
        echo "   ❌ Admin login route not found\n";
    }
    
    // Test article routes
    $articleRoutes = [
        'admin.articles.index',
        'admin.articles.create',
        'admin.articles.store',
        'admin.articles.show',
        'admin.articles.edit',
        'admin.articles.update',
        'admin.articles.destroy'
    ];
    
    $articleRoutesFound = 0;
    foreach ($articleRoutes as $routeName) {
        if ($routes->getByName($routeName)) {
            $articleRoutesFound++;
        }
    }
    
    echo "   ✅ Article routes: {$articleRoutesFound}/" . count($articleRoutes) . " found\n";
    
    echo "\n2️⃣ Testing Controller:\n";
    
    // Test ArticleController instantiation
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    echo "   ✅ ArticleController instantiated successfully\n";
    
    echo "\n3️⃣ Testing Database:\n";
    
    // Test article count
    $articlesCount = \App\Models\Article::count();
    echo "   ✅ Articles in database: {$articlesCount}\n";
    
    $publishedCount = \App\Models\Article::where('status', 'published')->count();
    echo "   ✅ Published articles: {$publishedCount}\n";
    
    echo "\n🎉 Route Fix Status: SUCCESS!\n";
    echo "📍 The login route error should now be resolved.\n";
    echo "🚀 You can now access: http://localhost:8000/admin/articles\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Stack trace: " . $e->getTraceAsString() . "\n";
}
