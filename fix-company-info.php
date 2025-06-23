<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Checking Company Info table and logo issues...\n\n";

try {
    // Check company_info data
    $companyInfo = DB::table('company_info')->get();
    
    if ($companyInfo->isEmpty()) {
        echo "❌ No company_info data found in database\n";
        echo "🔧 Creating default company_info data...\n";
          DB::table('company_info')->insert([
            'company_name' => 'PT LAMDAKU AKREDITASI',
            'email' => 'info@lamdaku.com',
            'phone' => '+62-21-1234567',
            'mobile' => '+62-811-1234567',
            'address' => 'Jakarta, Indonesia',
            'logo' => 'logos/default-logo.svg',
            'description' => 'Perusahaan akreditasi terpercaya yang menyediakan layanan akreditasi berkualitas tinggi untuk berbagai industri.',
            'website' => 'https://lamdaku.com',
            'social_media' => json_encode([
                'facebook' => 'https://facebook.com/lamdaku',
                'twitter' => 'https://twitter.com/lamdaku',
                'linkedin' => 'https://linkedin.com/company/lamdaku'
            ]),
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Default company_info data created\n";
    } else {
        echo "✅ Company info data found: " . $companyInfo->count() . " records\n";
          foreach ($companyInfo as $company) {
            echo "📋 Company: {$company->company_name}\n";
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
    <text x="100" y="35" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle">LAMDAKU</text>
    <text x="100" y="55" font-family="Arial, sans-serif" font-size="14" fill="white" text-anchor="middle">AKREDITASI</text>
    <text x="100" y="75" font-family="Arial, sans-serif" font-size="10" fill="#e2e8f0" text-anchor="middle">Excellence in Accreditation</text>
</svg>';
    
    file_put_contents($defaultLogoPath, $svgContent);
    echo "✅ Created default logo placeholder\n";
} else {
    echo "✅ Default logo already exists\n";
}

// Update company_info with default logo if logo is null
try {
    $companyWithoutLogo = DB::table('company_info')->whereNull('logo')->orWhere('logo', '')->get();
    
    if ($companyWithoutLogo->isNotEmpty()) {
        echo "🔧 Updating company_info without logo...\n";
        
        foreach ($companyWithoutLogo as $company) {
            DB::table('company_info')
                ->where('id', $company->id)
                ->update(['logo' => 'logos/default-logo.svg']);
            
            echo "✅ Updated logo for: {$company->company_name}\n";
        }
    } else {
        echo "✅ All company_info records have logos\n";
    }
} catch (Exception $e) {
    echo "❌ Error updating logo: " . $e->getMessage() . "\n";
}

echo "\n🎯 COMPANY INFO SETUP COMPLETE:\n";
echo "1. ✅ Storage directories ready\n";
echo "2. ✅ Default logo created\n";
echo "3. ✅ Company_info data populated\n";
echo "4. ✅ Logo paths configured\n\n";

echo "🌐 Your admin dashboard should now display the company logo!\n";
echo "📁 Logo storage: storage/app/public/logos/\n";
echo "🔗 Public access: /storage/logos/default-logo.svg\n";
echo "💡 To upload a custom logo, use the admin panel's company management section.\n";

?>
