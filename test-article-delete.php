<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

echo "🔧 Testing Article Delete Functionality...\n\n";

// Simulate being logged in as admin
session([
    'admin_authenticated' => true, 
    'admin_user_id' => 1, 
    'admin_role' => 'admin'
]);

echo "1️⃣ Session Setup:\n";
echo "   ✅ User ID: " . session('admin_user_id') . "\n";
echo "   ✅ Role: " . session('admin_role') . "\n\n";

echo "2️⃣ Testing Article Count:\n";
$totalArticles = Article::count();
echo "   📊 Total articles: {$totalArticles}\n";

if ($totalArticles === 0) {
    echo "   ❌ No articles found to test delete functionality\n";
    exit(1);
}

// Get first article to test delete logic (without actually deleting)
$testArticle = Article::first();
echo "   🎯 Test article: ID {$testArticle->id} - {$testArticle->title}\n\n";

echo "3️⃣ Testing Delete Authorization:\n";
$user = User::find(session('admin_user_id'));
if (!$user) {
    echo "   ❌ User not found\n";
    exit(1);
}

echo "   ✅ User found: {$user->name} ({$user->role})\n";

// Test authorization logic
if ($user->role === 'penulis' && $testArticle->author_id !== $user->id) {
    echo "   ⚠️  Penulis can only delete own articles\n";
    echo "   📝 Article author: {$testArticle->author_id}, Current user: {$user->id}\n";
} else {
    echo "   ✅ User has permission to delete this article\n";
}

echo "\n4️⃣ Testing File Cleanup Logic:\n";
$filesToDelete = [];

if ($testArticle->featured_image) {
    echo "   📷 Featured image: {$testArticle->featured_image}\n";
    $filesToDelete[] = $testArticle->featured_image;
}

if ($testArticle->gallery) {
    echo "   🖼️  Gallery images: " . count($testArticle->gallery) . " files\n";
    $filesToDelete = array_merge($filesToDelete, $testArticle->gallery);
}

if ($testArticle->og_image) {
    echo "   🌐 OG image: {$testArticle->og_image}\n";
    $filesToDelete[] = $testArticle->og_image;
}

echo "   📁 Total files to cleanup: " . count($filesToDelete) . "\n";

foreach ($filesToDelete as $filePath) {
    $exists = Storage::disk('public')->exists($filePath);
    echo "   - {$filePath}: " . ($exists ? "✅ exists" : "❌ missing") . "\n";
}

echo "\n5️⃣ Testing Route Resolution:\n";
try {
    $route = route('admin.articles.destroy', $testArticle->id);
    echo "   ✅ Delete route: {$route}\n";
} catch (Exception $e) {
    echo "   ❌ Route error: " . $e->getMessage() . "\n";
}

echo "\n6️⃣ Testing Controller Method:\n";
try {
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    echo "   ✅ ArticleController instantiated\n";
    
    if (method_exists($controller, 'destroy')) {
        echo "   ✅ destroy() method exists\n";
    } else {
        echo "   ❌ destroy() method missing\n";
    }
} catch (Exception $e) {
    echo "   ❌ Controller error: " . $e->getMessage() . "\n";
}

echo "\n🔍 POSSIBLE ISSUES:\n";
echo "   1. Check browser console for JavaScript errors\n";
echo "   2. Verify CSRF token is being sent correctly\n";
echo "   3. Check if middleware is blocking the request\n";
echo "   4. Confirm user has proper permissions\n";
echo "   5. Look for any validation errors\n";

echo "\n💡 DEBUGGING STEPS:\n";
echo "   1. Open browser developer tools (F12)\n";
echo "   2. Go to Network tab\n";
echo "   3. Try to delete an article\n";
echo "   4. Check if DELETE request is being sent\n";
echo "   5. Look at the response status and message\n";

echo "\n🎯 MANUAL TEST:\n";
echo "   1. Login as admin: http://localhost:8000/admin/login\n";
echo "   2. Go to articles: http://localhost:8000/admin/articles\n";
echo "   3. Click delete button and check browser console\n";
echo "   4. Report any error messages you see\n\n";

echo "✅ Delete functionality analysis complete!\n";
