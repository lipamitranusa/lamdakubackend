<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FAVICON UPDATE FROM COMPANY LOGO ===\n\n";

// Get current company info
$company = DB::table('company_info')->first();

if (!$company) {
    echo "❌ No company info found in database\n";
    exit(1);
}

if (!$company->logo) {
    echo "❌ No logo set in company info\n";
    exit(1);
}

$logoFile = $company->logo;
$logoPath = storage_path('app/public/logos/' . $logoFile);

echo "✅ Company: {$company->company_name}\n";
echo "✅ Logo file: {$logoFile}\n";

if (!file_exists($logoPath)) {
    echo "❌ Logo file not found at: {$logoPath}\n";
    exit(1);
}

echo "✅ Logo found at: {$logoPath}\n";

// Get image info
$imageInfo = getimagesize($logoPath);
if ($imageInfo) {
    echo "✅ Image size: {$imageInfo[0]}x{$imageInfo[1]}\n";
    echo "✅ File size: " . number_format(filesize($logoPath)) . " bytes\n";
}

// Backend favicon update
$backendFaviconIco = public_path('favicon.ico');
$backendFaviconPng = public_path('favicon.png');

if (copy($logoPath, $backendFaviconIco)) {
    echo "✅ Backend favicon.ico updated\n";
} else {
    echo "❌ Failed to update backend favicon.ico\n";
}

if (copy($logoPath, $backendFaviconPng)) {
    echo "✅ Backend favicon.png updated\n";
} else {
    echo "❌ Failed to update backend favicon.png\n";
}

// Frontend favicon update
$frontendPath = 'D:\laragon\www\LAMDAKU\accreditation-company-profile\public';
$frontendFaviconIco = $frontendPath . '\favicon.ico';
$frontendFaviconPng = $frontendPath . '\favicon.png';

if (is_dir($frontendPath)) {
    if (copy($logoPath, $frontendFaviconIco)) {
        echo "✅ Frontend favicon.ico updated\n";
    } else {
        echo "❌ Failed to update frontend favicon.ico\n";
    }
    
    if (copy($logoPath, $frontendFaviconPng)) {
        echo "✅ Frontend favicon.png updated\n";
    } else {
        echo "❌ Failed to update frontend favicon.png\n";
    }
} else {
    echo "⚠️  Frontend directory not found: {$frontendPath}\n";
}

echo "\n=== FAVICON UPDATE COMPLETE ===\n";
echo "Favicons have been updated to use the actual company logo.\n";
echo "Refresh your browser to see the changes.\n";
