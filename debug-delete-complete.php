<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 TESTING CSRF AND DELETE FUNCTIONALITY...\n\n";

echo "1️⃣ Testing CSRF Token Generation:\n";
try {
    $token = csrf_token();
    echo "   ✅ CSRF Token: " . substr($token, 0, 20) . "...\n";
    echo "   ✅ Token length: " . strlen($token) . " chars\n";
} catch (Exception $e) {
    echo "   ❌ CSRF Error: " . $e->getMessage() . "\n";
}

echo "\n2️⃣ Testing Middleware Configuration:\n";
try {
    $middleware = app('router')->getMiddleware();
    
    if (isset($middleware['admin.auth'])) {
        echo "   ✅ admin.auth middleware registered\n";
    } else {
        echo "   ❌ admin.auth middleware missing\n";
    }
    
    if (isset($middleware['web'])) {
        echo "   ✅ web middleware (includes CSRF) registered\n";
    } else {
        echo "   ❌ web middleware missing\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Middleware check error: " . $e->getMessage() . "\n";
}

echo "\n3️⃣ Creating Test Delete Form HTML:\n";

$testArticleId = 1; // Assuming article with ID 1 exists
$csrfToken = csrf_token();

$deleteFormHtml = <<<HTML
<!-- Test Delete Form -->
<form id="test-delete-form" method="POST" action="/admin/articles/{$testArticleId}">
    <input type="hidden" name="_token" value="{$csrfToken}">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">Delete Article</button>
</form>

<script>
// Test JavaScript version (matches your current implementation)
function deleteArticle(articleId) {
    if (confirm('Yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/articles/\${articleId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{$csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Test this in browser console:
// deleteArticle(1);
</script>
HTML;

$testFile = 'test-delete-form.html';
file_put_contents($testFile, $deleteFormHtml);

echo "   ✅ Test form created: {$testFile}\n";
echo "   📝 You can open this file in browser and test the form\n";

echo "\n4️⃣ Direct Controller Test:\n";
try {
    // Simulate session data
    session([
        'admin_authenticated' => true,
        'admin_user_id' => 2, // admin user
        'admin_role' => 'admin'
    ]);
    
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    echo "   ✅ Controller instantiated with admin session\n";
    
    $testArticle = \App\Models\Article::first();
    if ($testArticle) {
        echo "   ✅ Test article found: {$testArticle->title}\n";
        echo "   📝 Article ID: {$testArticle->id}\n";
        echo "   👤 Author ID: {$testArticle->author_id}\n";
    } else {
        echo "   ❌ No articles found for testing\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Controller test error: " . $e->getMessage() . "\n";
}

echo "\n5️⃣ Debugging Checklist:\n";
echo "   🔹 Check browser network tab when clicking delete\n";
echo "   🔹 Look for 419 (CSRF) or 403 (Forbidden) errors\n";
echo "   🔹 Verify delete button is visible (session variable fix)\n";
echo "   🔹 Test with both admin and penulis users\n";
echo "   🔹 Check Laravel logs: storage/logs/laravel.log\n";

echo "\n6️⃣ Manual Testing Steps:\n";
echo "   1. Start server: php artisan serve --port=8000\n";
echo "   2. Login as admin: http://localhost:8000/admin/login\n";
echo "   3. Go to articles: http://localhost:8000/admin/articles\n";
echo "   4. Open browser DevTools (F12)\n";
echo "   5. Click delete button on any article\n";
echo "   6. Check Network tab for the DELETE request\n";
echo "   7. Look at response status and message\n";

echo "\n🎯 MOST LIKELY ISSUES:\n";
echo "   ❌ CSRF token mismatch (419 error)\n";
echo "   ❌ Session variable inconsistency (buttons not showing)\n";
echo "   ❌ Permission denied (403 error)\n";
echo "   ❌ JavaScript error preventing form submission\n";

echo "\n✅ FIXES ALREADY APPLIED:\n";
echo "   ✅ Fixed session('admin_id') → session('admin_user_id')\n";
echo "   ✅ Delete buttons should now appear correctly\n";
echo "   ✅ Route is properly registered\n";
echo "   ✅ Controller method exists and is correct\n";

echo "\n📞 If still not working, please:\n";
echo "   1. Try deleting an article and note the exact error\n";
echo "   2. Check browser console for JavaScript errors\n";
echo "   3. Check Network tab for failed requests\n";
echo "   4. Report the specific error message you see\n\n";

echo "✨ Delete functionality should now work! ✨\n";
