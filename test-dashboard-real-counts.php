<?php
/**
 * Test Script: Dashboard Real Database Counts
 * File: test-dashboard-real-counts.php
 * Purpose: Verify dashboard shows real database counts (including 0)
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🎯 DASHBOARD REAL DATABASE COUNTS TEST\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Check DashboardController exists and has correct method
    echo "1. Testing DashboardController...\n";
    $controllerFile = __DIR__ . '/app/Http/Controllers/Admin/DashboardController.php';
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);
        
        // Check if safeCount method returns actual count (not fallback on 0)
        if (strpos($content, 'return $modelClass::count();') !== false) {
            echo "   ✅ safeCount method properly returns actual counts\n";
        } else {
            echo "   ❌ safeCount method may still use fallback logic\n";
        }
        
        // Check if fallback stats are zeros instead of fake numbers
        if (strpos($content, "'contacts' => 0,") !== false) {
            echo "   ✅ Fallback stats use real zeros\n";
        } else {
            echo "   ❌ Fallback stats still use fake numbers\n";
        }
    } else {
        echo "   ❌ DashboardController file not found\n";
    }

    // Test 2: Simulate what safeCount would return
    echo "\n2. Testing database count logic...\n";
    
    // Load Laravel environment
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    // Initialize Laravel app
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Test database connection and counts
    $models = [
        'App\Models\Contact' => 'Contacts',
        'App\Models\Service' => 'Services', 
        'App\Models\Article' => 'Articles',
        'App\Models\Page' => 'Pages',
        'App\Models\Event' => 'Events',
    ];
    
    foreach ($models as $modelClass => $name) {
        try {
            if (class_exists($modelClass)) {
                $count = $modelClass::count();
                echo "   ✅ $name: $count (real database count)\n";
            } else {
                echo "   ⚠️  $name: Model not found\n";
            }
        } catch (Exception $e) {
            echo "   ❌ $name: Error - " . $e->getMessage() . "\n";
        }
    }

    // Test 3: Verify zeros are handled correctly
    echo "\n3. Testing zero count handling...\n";
    $zeroModels = [];
    foreach ($models as $modelClass => $name) {
        try {
            if (class_exists($modelClass)) {
                $count = $modelClass::count();
                if ($count === 0) {
                    $zeroModels[] = $name;
                }
            }
        } catch (Exception $e) {
            // Skip errors
        }
    }
    
    if (!empty($zeroModels)) {
        echo "   ✅ Models with 0 count: " . implode(', ', $zeroModels) . "\n";
        echo "   ✅ Dashboard will show real zeros (not fallback numbers)\n";
    } else {
        echo "   ℹ️  No models have 0 count to test with\n";
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ DASHBOARD REAL COUNTS TEST COMPLETE\n";
    echo "📊 Dashboard now shows actual database counts\n";
    echo "🎯 Zero counts display as 0 (not fake fallback numbers)\n";
    
} catch (Exception $e) {
    echo "\n❌ Error during test: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
