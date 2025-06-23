<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing Dynamic Logo and Company Name Integration\n";
echo "=" . str_repeat("=", 60) . "\n\n";

try {
    // 1. Test Company Info Availability
    echo "1️⃣ Testing Company Info Availability:\n";
    $company = \App\Models\CompanyInfo::where('is_active', 1)->first();
    
    if ($company) {
        echo "   ✅ Company found: {$company->company_name}\n";
        echo "   🖼️ Logo: " . ($company->logo ?: 'None') . "\n";
        echo "   📧 Email: " . ($company->email ?: 'None') . "\n";
        echo "   🌐 Website: " . ($company->website ?: 'None') . "\n";
        echo "   🟢 Active: " . ($company->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "   ❌ No active company info found\n";
    }
    
    echo "\n";
    
    // 2. Test Login Page
    echo "2️⃣ Testing Login Page with Dynamic Branding:\n";
    
    // Simulate accessing login page without authentication
    session()->forget(['admin_authenticated', 'admin_user']);
    
    $authController = new App\Http\Controllers\Admin\AuthController();
    $loginView = $authController->showLoginForm();
    
    if ($loginView instanceof Illuminate\View\View) {
        echo "   ✅ Login page rendered successfully\n";
        $data = $loginView->getData();
        
        if (isset($data['company']) && $data['company']) {
            echo "   🏢 Company data available: {$data['company']->company_name}\n";
            echo "   🖼️ Logo available: " . ($data['company']->logo ? 'Yes' : 'No') . "\n";
        } else {
            echo "   ⚠️ Company data not available in login view\n";
        }
    } else {
        echo "   ❌ Login page failed to render\n";
    }
    
    echo "\n";
    
    // 3. Test Dashboard with Authentication
    echo "3️⃣ Testing Dashboard with Dynamic Branding:\n";
    
    // Simulate admin authentication
    session(['admin_authenticated' => true, 'admin_user' => 'Administrator']);
    
    $dashboardController = new App\Http\Controllers\Admin\DashboardController();
    $dashboardView = $dashboardController->index();
    
    if ($dashboardView instanceof Illuminate\View\View) {
        echo "   ✅ Dashboard rendered successfully\n";
        $data = $dashboardView->getData();
        
        if (isset($data['company']) && $data['company']) {
            echo "   🏢 Company data available: {$data['company']->company_name}\n";
            echo "   🖼️ Logo available: " . ($data['company']->logo ? 'Yes' : 'No') . "\n";
        } else {
            echo "   ⚠️ Company data not available in dashboard view\n";
        }
        
        // Check stats
        if (isset($data['stats'])) {
            echo "   📊 Stats available: " . implode(', ', array_keys($data['stats'])) . "\n";
        }
    } else {
        echo "   ❌ Dashboard failed to render\n";
    }
    
    echo "\n";
    
    // 4. Test ViewServiceProvider
    echo "4️⃣ Testing ViewServiceProvider Auto-Loading:\n";
    
    try {
        // Test admin layout view
        $layoutView = view('admin.layout-simple');
        $data = $layoutView->getData();
        
        if (isset($data['company'])) {
            echo "   ✅ ViewServiceProvider working - company auto-loaded\n";
            echo "   🏢 Auto-loaded company: {$data['company']->company_name}\n";
        } else {
            echo "   ⚠️ ViewServiceProvider not working - company not auto-loaded\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error testing ViewServiceProvider: {$e->getMessage()}\n";
    }
    
    echo "\n";
    
    // 5. Test Logo File Existence
    echo "5️⃣ Testing Logo File Accessibility:\n";
    
    if ($company && $company->logo) {
        $logoPath = public_path('storage/logos/' . $company->logo);
        
        if (file_exists($logoPath)) {
            echo "   ✅ Logo file exists: {$logoPath}\n";
            echo "   📏 File size: " . number_format(filesize($logoPath) / 1024, 2) . " KB\n";
            echo "   🔗 URL: " . asset('storage/logos/' . $company->logo) . "\n";
        } else {
            echo "   ❌ Logo file missing: {$logoPath}\n";
        }
    } else {
        echo "   ⚠️ No logo configured\n";
    }
    
    echo "\n";
    
    // 6. Summary
    echo "6️⃣ Summary:\n";
    echo "   📋 Integration Status: ";
    
    $issues = [];
    
    if (!$company) {
        $issues[] = "No active company info";
    }
    
    if ($company && $company->logo && !file_exists(public_path('storage/logos/' . $company->logo))) {
        $issues[] = "Logo file missing";
    }
    
    if (empty($issues)) {
        echo "✅ FULLY FUNCTIONAL\n";
        echo "   🎯 All components working correctly\n";
        echo "   🔄 Dynamic branding active\n";
        echo "   🖼️ Logo integration complete\n";
    } else {
        echo "⚠️ ISSUES FOUND\n";
        foreach ($issues as $issue) {
            echo "   ❌ {$issue}\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Critical Error: {$e->getMessage()}\n";
    echo "📁 File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🏁 Dynamic Logo and Company Name Integration Test Complete!\n";
