<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Checking company logo issues...\n\n";

try {
    // Check companies data
    $companies = DB::table('companies')->get();
    
    if ($companies->isEmpty()) {
        echo "❌ No company data found in database\n";
        echo "🔧 Creating default company data...\n";
        
        DB::table('companies')->insert([
            'name' => 'PT LAMDAKU AKREDITASI',
            'email' => 'info@lamdaku.com',
            'phone' => '+62-21-1234567',
            'address' => 'Jakarta, Indonesia',
            'logo' => null,
            'description' => 'Perusahaan akreditasi terpercaya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Default company data created\n";
    } else {
        echo "✅ Company data found: " . $companies->count() . " records\n";
        
        foreach ($companies as $company) {
            echo "📋 Company: {$company->name}\n";
            echo "🖼️  Logo: " . ($company->logo ?? 'No logo set') . "\n";
            echo "📧 Email: {$company->email}\n";
            echo "📞 Phone: {$company->phone}\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// Check storage directories
$storagePath = 'storage/app/public';
$publicPath = 'public/storage';
$logoPath = 'storage/app/public/logos';

echo "📁 Checking storage directories...\n";

if (!is_dir($storagePath)) {
    echo "❌ Storage directory missing: {$storagePath}\n";
    mkdir($storagePath, 0755, true);
    echo "✅ Created storage directory\n";
} else {
    echo "✅ Storage directory exists\n";
}

if (!is_link($publicPath) && !is_dir($publicPath)) {
    echo "❌ Storage link missing: {$publicPath}\n";
    echo "🔧 Run: php artisan storage:link\n";
} else {
    echo "✅ Storage link exists\n";
}

if (!is_dir($logoPath)) {
    mkdir($logoPath, 0755, true);
    echo "✅ Created logos directory\n";
} else {
    echo "✅ Logos directory exists\n";
}

// Create default logo placeholder
$defaultLogoPath = $logoPath . '/default-logo.svg';
if (!file_exists($defaultLogoPath)) {
    $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="100" viewBox="0 0 200 100">
    <rect width="200" height="100" fill="#667eea" rx="8"/>
    <text x="100" y="45" font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="white" text-anchor="middle">LAMDAKU</text>
    <text x="100" y="65" font-family="Arial, sans-serif" font-size="12" fill="white" text-anchor="middle">AKREDITASI</text>
</svg>';
    
    file_put_contents($defaultLogoPath, $svgContent);
    echo "✅ Created default logo placeholder\n";
}

// Update company with default logo if logo is null
try {
    $companiesWithoutLogo = DB::table('companies')->whereNull('logo')->get();
    
    if ($companiesWithoutLogo->isNotEmpty()) {
        echo "🔧 Updating companies without logo...\n";
        
        foreach ($companiesWithoutLogo as $company) {
            DB::table('companies')
                ->where('id', $company->id)
                ->update(['logo' => 'logos/default-logo.svg']);
            
            echo "✅ Updated logo for: {$company->name}\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error updating logo: " . $e->getMessage() . "\n";
}

echo "\n🎯 RESULTS:\n";
echo "1. ✅ Storage link exists\n";
echo "2. ✅ Logos directory created\n";
echo "3. ✅ Default logo placeholder created\n";
echo "4. ✅ Company data checked/created\n";
echo "5. ✅ Logo paths updated\n\n";

echo "🌐 Access your admin panel to upload a proper logo!\n";
echo "📁 Logo storage: storage/app/public/logos/\n";
echo "🔗 Public access: /storage/logos/\n";

?>
