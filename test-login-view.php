<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing login page view rendering...\n\n";

try {
    // Get the auth controller
    $controller = new App\Http\Controllers\Admin\AuthController();
    
    echo "📝 Controller created successfully\n";
    
    // Test showLoginForm method
    echo "🔄 Calling showLoginForm...\n";
    $response = $controller->showLoginForm();
    
    echo "✅ Login form rendered successfully!\n";
    echo "📄 Response type: " . get_class($response) . "\n";
    
    if ($response instanceof Illuminate\Http\Response) {
        echo "📊 Status code: " . $response->getStatusCode() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error rendering login page:\n";
    echo "📝 Message: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "🔍 Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔍 Checking company info availability...\n";

try {
    $company = \App\Models\CompanyInfo::where('is_active', 1)->first();
    
    if ($company) {
        echo "✅ Company info found:\n";
        echo "   🏢 Name: " . $company->company_name . "\n";
        echo "   🖼️ Logo: " . ($company->logo ?: 'None') . "\n";
        echo "   🟢 Active: " . ($company->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "⚠️ No active company info found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error checking company info: " . $e->getMessage() . "\n";
}

echo "\n✨ Test complete!\n";
