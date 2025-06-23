<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 TESTING DELETE CONFIRMATION FIX...\n\n";

echo "1️⃣ Testing Layout Script Support:\n";
$layoutPath = 'resources/views/admin/layout-simple.blade.php';
$layoutContent = file_get_contents($layoutPath);

if (strpos($layoutContent, '@stack(\'scripts\')') !== false) {
    echo "   ✅ @stack('scripts') found in layout\n";
} else {
    echo "   ❌ @stack('scripts') missing in layout\n";
}

if (strpos($layoutContent, '@yield(\'scripts\')') !== false) {
    echo "   ✅ @yield('scripts') found in layout\n";
} else {
    echo "   ❌ @yield('scripts') missing in layout\n";
}

echo "\n2️⃣ Testing Articles View Scripts:\n";
$articlesPath = 'resources/views/admin/articles/index.blade.php';
$articlesContent = file_get_contents($articlesPath);

if (strpos($articlesContent, 'function deleteArticle') !== false) {
    echo "   ✅ deleteArticle function found\n";
} else {
    echo "   ❌ deleteArticle function missing\n";
}

if (strpos($articlesContent, 'console.log') !== false) {
    echo "   ✅ Debug logging added\n";
} else {
    echo "   ❌ Debug logging missing\n";
}

if (strpos($articlesContent, 'INLINE ARTICLE SCRIPTS LOADED') !== false) {
    echo "   ✅ Inline script marker found\n";
} else {
    echo "   ❌ Inline script marker missing\n";
}

echo "\n3️⃣ Testing Session Variables:\n";
if (strpos($articlesContent, 'session(\'admin_user_id\')') !== false) {
    echo "   ✅ Correct session variable used\n";
} else {
    echo "   ❌ Incorrect session variable\n";
}

if (strpos($articlesContent, 'session(\'admin_id\')') !== false) {
    echo "   ⚠️  Old session variable still present\n";
} else {
    echo "   ✅ Old session variable removed\n";
}

echo "\n4️⃣ Testing Button Structure:\n";
if (strpos($articlesContent, 'onclick="deleteArticle(') !== false) {
    echo "   ✅ Delete button onclick found\n";
} else {
    echo "   ❌ Delete button onclick missing\n";
}

echo "\n5️⃣ Testing CSRF Token:\n";
if (strpos($articlesContent, 'csrf_token()') !== false) {
    echo "   ✅ CSRF token generation found\n";
} else {
    echo "   ❌ CSRF token missing\n";
}

echo "\n6️⃣ Manual Testing Instructions:\n";
echo "   🚀 Start server: php artisan serve --port=8000\n";
echo "   🔑 Login: http://localhost:8000/admin/login (admin/admin123)\n";
echo "   📰 Articles: http://localhost:8000/admin/articles\n";
echo "   🛠️  Open DevTools (F12) → Console tab\n";
echo "   👁️  Look for: '=== INLINE ARTICLE SCRIPTS LOADED ==='\n";
echo "   🗑️  Click delete button (trash icon)\n";
echo "   📢 Should see: 'Delete function called for article ID: X'\n";
echo "   ❓ Should see: Confirmation dialog\n";

echo "\n7️⃣ Expected Console Output:\n";
echo "   ✅ === INLINE ARTICLE SCRIPTS LOADED ===\n";
echo "   ✅ deleteArticle function available: function\n";
echo "   ✅ toggleFeatured function available: function\n";
echo "   ✅ deleteArticle function called with ID: X\n";

echo "\n8️⃣ If Still Not Working:\n";
echo "   🔍 Check for JavaScript errors in console\n";
echo "   🔍 Try manually in console: deleteArticle(1)\n";
echo "   🔍 Check if buttons are visible (session variable fix)\n";
echo "   🔍 Verify onclick attributes in page source\n";

echo "\n✨ Delete confirmation should now work! ✨\n";
