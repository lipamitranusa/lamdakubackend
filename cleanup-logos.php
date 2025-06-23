<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 CLEANING UP LOGO ISSUES...\n\n";

try {
    // Get all company info with logos
    $companies = DB::table('company_info')->whereNotNull('logo')->get();
    
    echo "📋 Found " . $companies->count() . " companies with logo data\n\n";
    
    foreach ($companies as $company) {
        echo "🏢 Checking: {$company->company_name}\n";
        echo "📁 Logo file: {$company->logo}\n";
        
        $logoPath = "storage/app/public/logos/{$company->logo}";
        $publicPath = "public/storage/logos/{$company->logo}";
        
        // Check if file exists in storage
        if (file_exists($logoPath)) {
            echo "✅ File exists in storage\n";
            
            // Check if accessible via public URL
            if (file_exists($publicPath)) {
                echo "✅ File accessible via public URL\n";
                echo "🌐 URL: " . asset("storage/logos/{$company->logo}") . "\n";
            } else {
                echo "❌ File not accessible via public URL\n";
                echo "🔧 Running storage:link...\n";
                Artisan::call('storage:link');
            }
        } else {
            echo "❌ File not found in storage\n";
            echo "🔧 Setting logo to default...\n";
            
            // Update to use default logo
            DB::table('company_info')
                ->where('id', $company->id)
                ->update(['logo' => 'lamdaku-official-logo.svg']);
            
            echo "✅ Updated to use default logo\n";
        }
        echo "---\n";
    }
    
    // Check for companies without logos
    $companiesWithoutLogo = DB::table('company_info')->whereNull('logo')->orWhere('logo', '')->get();
    
    if ($companiesWithoutLogo->isNotEmpty()) {
        echo "\n🔧 Found " . $companiesWithoutLogo->count() . " companies without logos\n";
        echo "🔧 Setting default logos...\n";
        
        foreach ($companiesWithoutLogo as $company) {
            DB::table('company_info')
                ->where('id', $company->id)
                ->update(['logo' => 'lamdaku-official-logo.svg']);
            
            echo "✅ Set default logo for: {$company->company_name}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Verify all logos are working
echo "\n🎯 FINAL VERIFICATION:\n";
$allCompanies = DB::table('company_info')->get();

foreach ($allCompanies as $company) {
    if ($company->logo) {
        $logoUrl = asset("storage/logos/{$company->logo}");
        $logoPath = "public/storage/logos/{$company->logo}";
        
        echo "🏢 {$company->company_name}\n";
        echo "📁 Logo: {$company->logo}\n";
        echo "🌐 URL: {$logoUrl}\n";
        echo "✅ File exists: " . (file_exists($logoPath) ? "YES" : "NO") . "\n";
        echo "---\n";
    }
}

echo "\n🎉 LOGO CLEANUP COMPLETE!\n";
echo "💡 Test your logos at: http://localhost:8000/admin/company\n";

?>
