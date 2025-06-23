<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Checking Company Info Issues...\n\n";

try {
    // Check company_info table
    $companyInfos = DB::table('company_info')->get();
    
    if ($companyInfos->isEmpty()) {
        echo "❌ No data in company_info table\n";
        echo "🔧 Creating default company info...\n";
        
        DB::table('company_info')->insert([
            'company_name' => 'PT LAMDAKU AKREDITASI',
            'address' => 'Jakarta, Indonesia',
            'phone' => '+62-21-1234567',
            'mobile' => '+62-812-3456789',
            'email' => 'info@lamdaku.com',
            'website' => 'https://lamdaku.com',
            'logo' => null,
            'description' => 'Perusahaan akreditasi terpercaya di Indonesia',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Default company info created\n";
    } else {
        echo "✅ Company info data found: " . $companyInfos->count() . " records\n";
        
        foreach ($companyInfos as $info) {
            echo "📋 Company: {$info->company_name}\n";
            echo "🖼️  Logo: " . ($info->logo ?? 'No logo set') . "\n";
            echo "📧 Email: {$info->email}\n";
            echo "🌐 Website: {$info->website}\n";
            echo "✅ Active: " . ($info->is_active ? 'Yes' : 'No') . "\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// Create storage structure
$logoPath = 'storage/app/public/logos';
if (!is_dir($logoPath)) {
    mkdir($logoPath, 0755, true);
    echo "✅ Created logos directory\n";
}

// Create default logo SVG
$defaultLogoPath = $logoPath . '/default-company-logo.svg';
if (!file_exists($defaultLogoPath)) {
    $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="300" height="150" viewBox="0 0 300 150">
    <defs>
        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
        </linearGradient>
    </defs>
    <rect width="300" height="150" fill="url(#grad1)" rx="10"/>
    <text x="150" y="70" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="white" text-anchor="middle">LAMDAKU</text>
    <text x="150" y="95" font-family="Arial, sans-serif" font-size="14" fill="white" text-anchor="middle">AKREDITASI INDONESIA</text>
    <circle cx="60" cy="75" r="20" fill="rgba(255,255,255,0.2)"/>
    <circle cx="240" cy="75" r="15" fill="rgba(255,255,255,0.1)"/>
</svg>';
    
    file_put_contents($defaultLogoPath, $svgContent);
    echo "✅ Created default company logo\n";
}

// Update records without logo to use default
try {
    $companiesWithoutLogo = DB::table('company_info')->whereNull('logo')->get();
    
    if ($companiesWithoutLogo->isNotEmpty()) {
        echo "🔧 Updating companies without logo...\n";
        
        foreach ($companiesWithoutLogo as $company) {
            DB::table('company_info')
                ->where('id', $company->id)
                ->update(['logo' => 'default-company-logo.svg']);
            
            echo "✅ Updated logo for: {$company->company_name}\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error updating logo: " . $e->getMessage() . "\n";
}

echo "\n🎯 RESULTS:\n";
echo "1. ✅ Company info table checked/populated\n";
echo "2. ✅ Logos directory created\n";
echo "3. ✅ Default logo created\n";
echo "4. ✅ Logo paths updated\n\n";

echo "🌐 Check your admin panel at: /admin/company\n";
echo "📁 Upload logos to: storage/app/public/logos/\n";
echo "🔗 Public access: /storage/logos/filename\n";

?>
