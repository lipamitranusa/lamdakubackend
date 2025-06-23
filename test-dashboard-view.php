<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing admin dashboard functionality...\n\n";

// Simulate admin authentication
session(['admin_authenticated' => true, 'admin_user' => 'Administrator']);

try {
    // Get the dashboard controller
    $controller = new App\Http\Controllers\Admin\DashboardController();
    
    echo "📝 Dashboard controller created successfully\n";
    
    // Test index method
    echo "🔄 Calling dashboard index...\n";
    $response = $controller->index();
    
    echo "✅ Dashboard rendered successfully!\n";
    echo "📄 Response type: " . get_class($response) . "\n";
    
    if ($response instanceof Illuminate\View\View) {
        echo "🎯 View name: " . $response->getName() . "\n";
        $data = $response->getData();
        echo "📊 View data keys: " . implode(', ', array_keys($data)) . "\n";
        
        if (isset($data['company']) && $data['company']) {
            echo "🏢 Company in dashboard: " . $data['company']->company_name . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error rendering dashboard:\n";
    echo "📝 Message: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "🔍 Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔍 Testing ViewServiceProvider...\n";

try {
    // Test that the view composer is working
    $view = view('admin.layout-simple');
    $data = $view->getData();
    
    if (isset($data['company'])) {
        echo "✅ Company info auto-loaded via ViewServiceProvider\n";
        echo "   🏢 Name: " . $data['company']->company_name . "\n";
    } else {
        echo "⚠️ Company info not auto-loaded\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error testing ViewServiceProvider: " . $e->getMessage() . "\n";
}

echo "\n✨ Test complete!\n";
