<?php
echo "🔍 COMPREHENSIVE SYSTEM STATUS CHECK\n";
echo "====================================\n\n";

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Database Check
echo "1. 📊 DATABASE STATUS:\n";
try {
    $company = DB::table('company_info')->first();
    if ($company) {
        echo "   ✅ Company Info: " . $company->company_name . "\n";
        echo "   ✅ Logo File: " . $company->logo . "\n";
        echo "   ✅ Active Status: " . ($company->is_active ? 'Active' : 'Inactive') . "\n";
    } else {
        echo "   ❌ No company info found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database Error: " . $e->getMessage() . "\n";
}

// 2. Storage Check
echo "\n2. 📁 STORAGE STATUS:\n";
$logoFile = $company->logo;
$storagePath = storage_path('app/public/logos/' . $logoFile);
$publicPath = public_path('storage/logos/' . $logoFile);

echo "   Storage Path: " . $storagePath . "\n";
echo "   Public Path: " . $publicPath . "\n";
echo "   Storage File Exists: " . (file_exists($storagePath) ? '✅ YES' : '❌ NO') . "\n";
echo "   Public Access: " . (file_exists($publicPath) ? '✅ YES' : '❌ NO') . "\n";

// 3. Logo URL Check
echo "\n3. 🖼️ LOGO URL STATUS:\n";
$logoUrl = asset('storage/logos/' . $logoFile);
echo "   Logo URL: " . $logoUrl . "\n";
echo "   File Size: " . (file_exists($publicPath) ? filesize($publicPath) . ' bytes' : 'N/A') . "\n";

// 4. Favicon Check
echo "\n4. 🌐 FAVICON STATUS:\n";
$faviconIco = public_path('favicon.ico');
$faviconPng = public_path('favicon.png');
echo "   Backend favicon.ico: " . (file_exists($faviconIco) ? '✅ YES' : '❌ NO') . "\n";
echo "   Backend favicon.png: " . (file_exists($faviconPng) ? '✅ YES' : '❌ NO') . "\n";

// 5. Upload Directory Check
echo "\n5. 📂 UPLOAD DIRECTORY STATUS:\n";
$logosDir = storage_path('app/public/logos');
echo "   Logos Directory: " . (is_dir($logosDir) ? '✅ EXISTS' : '❌ MISSING') . "\n";
echo "   Directory Writable: " . (is_writable($logosDir) ? '✅ YES' : '❌ NO') . "\n";

// 6. Routes Check
echo "\n6. 🛣️ ROUTES STATUS:\n";
echo "   Admin Dashboard: http://lamdaku-cms-backend.test/admin/dashboard\n";
echo "   Company Management: http://lamdaku-cms-backend.test/admin/company\n";
echo "   Logo Direct Access: " . $logoUrl . "\n";

echo "\n🎯 SUMMARY:\n";
echo "   Logo Upload: " . (file_exists($storagePath) ? '✅ WORKING' : '❌ BROKEN') . "\n";
echo "   Logo Display: " . (file_exists($publicPath) ? '✅ WORKING' : '❌ BROKEN') . "\n";
echo "   Favicon Setup: " . (file_exists($faviconIco) ? '✅ COMPLETE' : '❌ INCOMPLETE') . "\n";
echo "   Database: " . ($company ? '✅ POPULATED' : '❌ EMPTY') . "\n";

echo "\n🚀 READY FOR TESTING!\n";
?>
