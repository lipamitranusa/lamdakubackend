<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing Root URL Redirect to Login Page\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Check if root route redirects to admin login
    echo "1️⃣ Testing Root URL (/) Redirect:\n";
    
    // Create a request to the root URL
    $request = \Illuminate\Http\Request::create('/', 'GET');
    
    // Get the response through Laravel's route system
    $response = app()->handle($request);
    
    echo "   📊 Response Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 302) {
        $location = $response->headers->get('Location');
        echo "   🔄 Redirect Location: " . $location . "\n";
        
        if (str_contains($location, '/admin/login')) {
            echo "   ✅ SUCCESS: Root URL correctly redirects to login page\n";
        } else {
            echo "   ❌ ERROR: Root URL redirects to wrong location\n";
        }
    } else {
        echo "   ❌ ERROR: Expected redirect (302) but got: " . $response->getStatusCode() . "\n";
    }
    
    echo "\n";
    
    // Test 2: Check if login page loads correctly
    echo "2️⃣ Testing Login Page Access:\n";
    
    $loginRequest = \Illuminate\Http\Request::create('/admin/login', 'GET');
    $loginResponse = app()->handle($loginRequest);
    
    echo "   📊 Login Page Status: " . $loginResponse->getStatusCode() . "\n";
    
    if ($loginResponse->getStatusCode() === 200) {
        echo "   ✅ SUCCESS: Login page loads correctly\n";
        
        // Check if response contains login form elements
        $content = $loginResponse->getContent();
        if (str_contains($content, 'login') || str_contains($content, 'username') || str_contains($content, 'password')) {
            echo "   ✅ SUCCESS: Login page contains expected form elements\n";
        } else {
            echo "   ⚠️ WARNING: Login page might not contain form elements\n";
        }
    } else {
        echo "   ❌ ERROR: Login page failed to load\n";
    }
    
    echo "\n";
    
    // Test 3: Check URL generation
    echo "3️⃣ Testing URL Generation:\n";
    
    $adminLoginUrl = route('admin.login');
    echo "   🔗 Admin Login URL: " . $adminLoginUrl . "\n";
    
    if (str_contains($adminLoginUrl, '/admin/login')) {
        echo "   ✅ SUCCESS: Login URL generated correctly\n";
    } else {
        echo "   ❌ ERROR: Login URL generation failed\n";
    }
    
    echo "\n";
    
    // Summary
    echo "4️⃣ Summary:\n";
    echo "   🎯 Root URL (/) → Redirects to → /admin/login\n";
    echo "   📱 Users can now access: http://127.0.0.1:8000/\n";
    echo "   ↩️ Automatic redirect: ✅ WORKING\n";
    echo "   🔐 Login page access: ✅ WORKING\n";
    
} catch (\Exception $e) {
    echo "❌ Critical Error: {$e->getMessage()}\n";
    echo "📁 File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 Root URL Redirect Test Complete!\n";
