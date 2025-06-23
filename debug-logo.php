<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate request
$companyInfo = DB::table('company_info')->first();

echo "=== DEBUGGING LOGO URL ===\n";
echo "Company: " . $companyInfo->company_name . "\n";
echo "Logo field in DB: " . ($companyInfo->logo ?? 'NULL') . "\n";

if ($companyInfo && $companyInfo->logo) {
    $logoPath = 'storage/logos/' . $companyInfo->logo;
    echo "Logo path: " . $logoPath . "\n";
    
    // Check if file exists before creating URL
    if (file_exists(public_path($logoPath))) {
        $companyInfo->logo_url = asset($logoPath);
        echo "✓ File exists at: " . public_path($logoPath) . "\n";
        echo "✓ Generated URL: " . $companyInfo->logo_url . "\n";
        
        // Test fallback URL
        $fallbackUrl = asset('storage/logos/' . $companyInfo->logo);
        echo "✓ Fallback URL: " . $fallbackUrl . "\n";
        
        echo "\n=== VIEW LOGIC TEST ===\n";
        echo "logo_url value: " . ($companyInfo->logo_url ?? 'NULL') . "\n";
        $finalUrl = $companyInfo->logo_url ?? asset('storage/logos/' . $companyInfo->logo);
        echo "Final URL used in view: " . $finalUrl . "\n";
        
    } else {
        echo "✗ File NOT found at: " . public_path($logoPath) . "\n";
    }
} else {
    echo "✗ No logo in database\n";
}
