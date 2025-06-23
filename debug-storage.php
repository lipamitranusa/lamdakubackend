<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUGGING LOGO STORAGE ===\n";

// 1. Cek data di database
$company = DB::table('company_info')->first();
echo "Company: " . $company->company_name . "\n";
echo "Logo field in DB: " . ($company->logo ?? 'NULL') . "\n";

// 2. Cek struktur direktori storage
echo "\n=== STORAGE STRUCTURE ===\n";
$storagePath = 'storage/app/public';
$logosPath = 'storage/app/public/logos';

echo "Storage path exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Logos path exists: " . (is_dir($logosPath) ? 'YES' : 'NO') . "\n";

if (is_dir($logosPath)) {
    echo "\nFiles in logos directory:\n";
    $files = scandir($logosPath);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $size = filesize($logosPath . '/' . $file);
            echo "- {$file} ({$size} bytes)\n";
        }
    }
}

// 3. Cek symbolic link
echo "\n=== PUBLIC ACCESS ===\n";
$publicStoragePath = 'public/storage';
echo "Public storage link exists: " . (file_exists($publicStoragePath) ? 'YES' : 'NO') . "\n";
echo "Public storage is link: " . (is_link($publicStoragePath) ? 'YES' : 'NO') . "\n";

if (file_exists($publicStoragePath . '/logos')) {
    echo "Public logos accessible: YES\n";
    echo "Files accessible via public:\n";
    $publicFiles = scandir($publicStoragePath . '/logos');
    foreach ($publicFiles as $file) {
        if ($file != '.' && $file != '..') {
            echo "- {$file}\n";
        }
    }
} else {
    echo "Public logos accessible: NO\n";
}

// 4. Test specific logo file
if ($company->logo) {
    echo "\n=== TESTING CURRENT LOGO ===\n";
    $logoFile = $company->logo;
    $storageLogo = $logosPath . '/' . $logoFile;
    $publicLogo = $publicStoragePath . '/logos/' . $logoFile;
    
    echo "Logo file: {$logoFile}\n";
    echo "Storage path: {$storageLogo}\n";
    echo "Storage exists: " . (file_exists($storageLogo) ? 'YES' : 'NO') . "\n";
    echo "Public path: {$publicLogo}\n";
    echo "Public exists: " . (file_exists($publicLogo) ? 'YES' : 'NO') . "\n";
    
    if (function_exists('asset')) {
        echo "Generated URL: " . asset('storage/logos/' . $logoFile) . "\n";
    }
}

echo "\n=== RECOMMENDATIONS ===\n";
if (!file_exists($publicStoragePath)) {
    echo "❌ Run: php artisan storage:link\n";
}
if ($company->logo && !file_exists($logosPath . '/' . $company->logo)) {
    echo "❌ Logo file missing, update database or upload new logo\n";
}

?>
