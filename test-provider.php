<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🧪 Testing ViewServiceProvider registration...\n\n";

try {
    require 'vendor/autoload.php';
    
    // Bootstrap Laravel
    $app = require 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Check if ViewServiceProvider is registered
    $providers = config('app.providers') ?? [];
    echo "📋 Checking providers...\n";
    
    // Check bootstrap/providers.php
    $bootstrapProviders = require 'bootstrap/providers.php';
    echo "📦 Bootstrap providers: " . implode(', ', $bootstrapProviders) . "\n";
    
    if (in_array('App\\Providers\\ViewServiceProvider', $bootstrapProviders)) {
        echo "✅ ViewServiceProvider is registered\n";
    } else {
        echo "❌ ViewServiceProvider is NOT registered\n";
    }
    
    // Test company info availability
    $company = \App\Models\CompanyInfo::where('is_active', 1)->first();
    if ($company) {
        echo "✅ Company info available: " . $company->company_name . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✨ Test complete!\n";
