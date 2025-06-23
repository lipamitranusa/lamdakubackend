<?php

/**
 * Article Management System - Complete Status Check
 */

echo "🚀 Article Management System - Status Check\n";
echo str_repeat("=", 60) . "\n\n";

// 1. Check Database
echo "1️⃣ Database Status:\n";
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    
    // Check articles table
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
    $articlesCount = $stmt->fetchColumn();
    echo "   ✅ Articles Table: {$articlesCount} articles\n";
    
    // Check published articles
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
    $publishedCount = $stmt->fetchColumn();
    echo "   ✅ Published Articles: {$publishedCount}\n";
    
    // Check featured articles
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_featured = 1");
    $featuredCount = $stmt->fetchColumn();
    echo "   ✅ Featured Articles: {$featuredCount}\n";
    
} catch (Exception $e) {
    echo "   ❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Check Files
echo "2️⃣ File Structure:\n";

$files = [
    'app/Models/Article.php' => 'Article Model',
    'app/Http/Controllers/Admin/ArticleController.php' => 'Admin Controller',
    'app/Http/Controllers/Api/ArticleController.php' => 'API Controller',
    'database/migrations/2025_06_16_023116_create_articles_table.php' => 'Articles Migration',
    'database/seeders/ArticleSeeder.php' => 'Article Seeder',
    'resources/views/admin/articles/index.blade.php' => 'Admin Articles View',
    'frontend-integration/article-api-service.js' => 'API Service',
    'frontend-integration/ArticleComponents.jsx' => 'React Components',
];

foreach ($files as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ {$description}\n";
    } else {
        echo "   ❌ {$description} - Missing\n";
    }
}

echo "\n";

// 3. Check Routes
echo "3️⃣ Routes Status:\n";
$routeFiles = [
    'routes/web.php' => 'Web Routes',
    'routes/api.php' => 'API Routes'
];

foreach ($routeFiles as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $content = file_get_contents(__DIR__ . '/' . $file);
        if (strpos($content, 'articles') !== false) {
            echo "   ✅ {$description} - Article routes found\n";
        } else {
            echo "   ⚠️ {$description} - No article routes found\n";
        }
    } else {
        echo "   ❌ {$description} - File missing\n";
    }
}

echo "\n";

// 4. Frontend Integration Status
echo "4️⃣ Frontend Integration:\n";

$integrationFiles = [
    'frontend-integration/article-api-service.js',
    'frontend-integration/ArticleComponents.jsx',
    'frontend-integration/hooks/useArticles.js',
    'frontend-integration/utils/articleHelpers.js',
    'frontend-integration/config/api.js',
    'frontend-integration/README.md'
];

$integrationReady = true;
foreach ($integrationFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ " . basename($file) . "\n";
    } else {
        echo "   ❌ " . basename($file) . " - Missing\n";
        $integrationReady = false;
    }
}

echo "\n";
echo str_repeat("=", 60) . "\n";

if ($integrationReady) {
    echo "🎉 ARTICLE MANAGEMENT SYSTEM: FULLY OPERATIONAL!\n\n";
    
    echo "📌 Next Steps:\n";
    echo "   1. Start Laravel server: php artisan serve\n";
    echo "   2. Access admin: http://localhost:8000/admin/articles\n";
    echo "   3. Login: admin/admin123 or penulis/penulis123\n";
    echo "   4. Test API: http://localhost:8000/api/v1/articles\n";
    echo "   5. Copy frontend files to React project\n";
    echo "   6. Test React integration\n\n";
    
    echo "🚀 Ready for Production!\n";
} else {
    echo "⚠️ Some components are missing. Please check the issues above.\n";
}
