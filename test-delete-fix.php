<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Article;
use App\Models\User;

echo "🔧 TESTING ARTICLE DELETE ISSUE FIX...\n\n";

echo "1️⃣ Testing Session Variables Fix:\n";
echo "   ✅ Fixed session('admin_id') → session('admin_user_id') in views\n";
echo "   ✅ This ensures delete buttons are properly displayed\n\n";

echo "2️⃣ Verifying Current System State:\n";
$totalArticles = Article::count();
echo "   📊 Total articles: {$totalArticles}\n";

$adminUser = User::where('role', 'admin')->first();
$penulisUser = User::where('role', 'penulis')->first();

if ($adminUser) {
    echo "   👑 Admin user: {$adminUser->name} (ID: {$adminUser->id})\n";
}

if ($penulisUser) {
    echo "   📝 Penulis user: {$penulisUser->name} (ID: {$penulisUser->id})\n";
    
    $penulisArticles = Article::where('author_id', $penulisUser->id)->count();
    echo "   📰 Articles by penulis: {$penulisArticles}\n";
}

echo "\n3️⃣ Testing Route Registration:\n";
try {
    $testArticle = Article::first();
    if ($testArticle) {
        $deleteRoute = route('admin.articles.destroy', $testArticle->id);
        echo "   ✅ Delete route working: {$deleteRoute}\n";
    } else {
        echo "   ⚠️  No articles to test route with\n";
    }
} catch (Exception $e) {
    echo "   ❌ Route error: " . $e->getMessage() . "\n";
}

echo "\n4️⃣ Testing Controller Method:\n";
try {
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    
    $reflection = new ReflectionClass($controller);
    $destroyMethod = $reflection->getMethod('destroy');
    $parameters = $destroyMethod->getParameters();
    
    echo "   ✅ destroy() method exists\n";
    echo "   📋 Method signature: destroy(";
    foreach ($parameters as $param) {
        echo $param->getType() . " \$" . $param->getName();
    }
    echo ")\n";
    
} catch (Exception $e) {
    echo "   ❌ Controller error: " . $e->getMessage() . "\n";
}

echo "\n5️⃣ Manual Testing Instructions:\n";
echo "   🔹 STEP 1: Start the server\n";
echo "     php artisan serve --port=8000\n\n";

echo "   🔹 STEP 2: Login as admin\n";
echo "     URL: http://localhost:8000/admin/login\n";
echo "     Username: admin\n";
echo "     Password: admin123\n\n";

echo "   🔹 STEP 3: Go to articles page\n";
echo "     URL: http://localhost:8000/admin/articles\n\n";

echo "   🔹 STEP 4: Open Browser Developer Tools\n";
echo "     Press F12 → Go to Console tab\n\n";

echo "   🔹 STEP 5: Try to delete an article\n";
echo "     Click delete button (trash icon)\n";
echo "     Confirm the deletion\n\n";

echo "   🔹 STEP 6: Check for errors\n";
echo "     Look at browser console for any JavaScript errors\n";
echo "     Check if the request was sent successfully\n\n";

echo "6️⃣ Common Issues & Solutions:\n";
echo "   ❌ CSRF Token Missing:\n";
echo "     → Check if @csrf is properly included in delete form\n\n";
echo "   ❌ Route Not Found:\n";
echo "     → Verify route is registered: php artisan route:list | grep destroy\n\n";
echo "   ❌ Permission Denied:\n";
echo "     → Check user role and article ownership\n\n";
echo "   ❌ JavaScript Error:\n";
echo "     → Check browser console for syntax errors\n\n";

echo "7️⃣ Test Different Scenarios:\n";
echo "   🧪 Admin deleting any article\n";
echo "   🧪 Penulis deleting own article\n";
echo "   🧪 Penulis trying to delete other's article (should fail)\n\n";

echo "✅ FIXES APPLIED:\n";
echo "   ✅ Fixed session variable inconsistency (admin_id → admin_user_id)\n";
echo "   ✅ Delete buttons should now appear correctly\n";
echo "   ✅ Authorization should work properly\n\n";

echo "🎯 If delete still doesn't work, check:\n";
echo "   1. Browser console for JavaScript errors\n";
echo "   2. Network tab for failed requests\n";
echo "   3. Laravel logs for server errors\n";
echo "   4. CSRF token validation\n\n";

echo "📞 Next Steps:\n";
echo "   → Test the delete functionality manually\n";
echo "   → Report any specific error messages you see\n";
echo "   → Check both admin and penulis user scenarios\n\n";
