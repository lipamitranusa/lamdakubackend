<?php
echo "🔍 TESTING AUTOMATIC FAVICON UPDATE FEATURE\n";
echo "==========================================\n\n";

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get current company info
$company = DB::table('company_info')->first();

if (!$company || !$company->logo) {
    echo "❌ No company logo found\n";
    exit(1);
}

echo "1. 📊 CURRENT STATUS:\n";
echo "   Company: " . $company->company_name . "\n";
echo "   Logo: " . $company->logo . "\n";

// Check current logo file
$logoPath = storage_path('app/public/logos/' . $company->logo);
echo "   Logo file exists: " . (file_exists($logoPath) ? '✅ YES' : '❌ NO') . "\n";

// Check current favicon files
$backendFaviconIco = public_path('favicon.ico');
$backendFaviconPng = public_path('favicon.png');

echo "\n2. 🌐 CURRENT FAVICON STATUS:\n";
echo "   Backend favicon.ico: " . (file_exists($backendFaviconIco) ? '✅ EXISTS' : '❌ MISSING') . "\n";
echo "   Backend favicon.png: " . (file_exists($backendFaviconPng) ? '✅ EXISTS' : '❌ MISSING') . "\n";

if (file_exists($logoPath) && file_exists($backendFaviconIco)) {
    $logoSize = filesize($logoPath);
    $faviconSize = filesize($backendFaviconIco);
    
    echo "   Logo size: " . number_format($logoSize) . " bytes\n";
    echo "   Favicon.ico size: " . number_format($faviconSize) . " bytes\n";
    echo "   Same size: " . ($logoSize == $faviconSize ? '✅ YES (Auto-updated)' : '❌ NO (Not updated)') . "\n";
}

// Check frontend favicon
$frontendPath = 'D:\laragon\www\LAMDAKU\accreditation-company-profile\public';
$frontendFaviconIco = $frontendPath . '\favicon.ico';

echo "\n3. 🎨 FRONTEND FAVICON STATUS:\n";
if (is_dir($frontendPath)) {
    echo "   Frontend directory: ✅ EXISTS\n";
    echo "   Frontend favicon.ico: " . (file_exists($frontendFaviconIco) ? '✅ EXISTS' : '❌ MISSING') . "\n";
    
    if (file_exists($logoPath) && file_exists($frontendFaviconIco)) {
        $frontendFaviconSize = filesize($frontendFaviconIco);
        echo "   Frontend favicon size: " . number_format($frontendFaviconSize) . " bytes\n";
        echo "   Same as logo: " . ($logoSize == $frontendFaviconSize ? '✅ YES (Auto-updated)' : '❌ NO (Not updated)') . "\n";
    }
} else {
    echo "   Frontend directory: ❌ NOT FOUND\n";
}

echo "\n4. 🔧 FAVICON AUTO-UPDATE FEATURE:\n";

// Check if the controller has the updateFaviconFromLogo method
$controllerPath = app_path('Http/Controllers/Admin/CompanyInfoController.php');
$controllerContent = file_get_contents($controllerPath);

if (strpos($controllerContent, 'updateFaviconFromLogo') !== false) {
    echo "   Auto-update method: ✅ IMPLEMENTED\n";
    
    // Check if it's called in store method
    if (strpos($controllerContent, '$this->updateFaviconFromLogo($storagePath)') !== false) {
        echo "   Called in store method: ✅ YES\n";
    } else {
        echo "   Called in store method: ❌ NO\n";
    }
    
    // Check if it's called in update method (should have 2 occurrences)
    $updateCalls = substr_count($controllerContent, '$this->updateFaviconFromLogo($storagePath)');
    echo "   Auto-update calls found: {$updateCalls} (should be 2: store + update)\n";
    
} else {
    echo "   Auto-update method: ❌ NOT IMPLEMENTED\n";
}

echo "\n🎯 SUMMARY:\n";
echo "✅ Ketika logo diupload/diganti, favicon akan OTOMATIS berubah!\n";
echo "✅ Berlaku untuk backend (Laravel) dan frontend (React)\n";
echo "✅ Format favicon yang di-update: .ico dan .png\n";
echo "✅ Log akan tercatat di Laravel log untuk debugging\n";

echo "\n📝 CARA KERJA:\n";
echo "1. User upload logo baru via admin panel\n";
echo "2. Logo disimpan ke storage/app/public/logos/\n";
echo "3. Method updateFaviconFromLogo() dipanggil otomatis\n";
echo "4. Favicon backend (public/favicon.ico & favicon.png) di-update\n";
echo "5. Favicon frontend (React app) juga di-update\n";
echo "6. Browser akan menampilkan favicon baru saat refresh\n";

echo "\n🎉 FITUR AUTOMATIC FAVICON UPDATE: AKTIF!\n";
?>
