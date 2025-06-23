<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get current company logo
$company = DB::table('company_info')->first();
if (!$company || !$company->logo) {
    echo "No company logo found in database.\n";
    exit;
}

$logoFile = $company->logo;
$logoPath = storage_path('app/public/logos/' . $logoFile);

echo "Company: " . $company->company_name . "\n";
echo "Logo file: " . $logoFile . "\n";
echo "Logo path: " . $logoPath . "\n";
echo "File exists: " . (file_exists($logoPath) ? 'YES' : 'NO') . "\n";

if (file_exists($logoPath)) {
    $fileInfo = getimagesize($logoPath);
    echo "Image size: " . $fileInfo[0] . "x" . $fileInfo[1] . "\n";
    echo "Image type: " . $fileInfo['mime'] . "\n";
    echo "File size: " . number_format(filesize($logoPath)) . " bytes\n";
    
    // Copy the actual logo to public folder as favicon base
    $publicFaviconPath = public_path('company-favicon.png');
    copy($logoPath, $publicFaviconPath);
    echo "Copied to: " . $publicFaviconPath . "\n";
    
    // Create ICO from the actual logo
    $faviconIcoPath = public_path('favicon.ico');
    copy($logoPath, $faviconIcoPath);
    echo "Created favicon.ico from actual logo\n";
    
} else {
    echo "Logo file not found!\n";
}
