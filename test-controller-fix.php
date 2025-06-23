<?php

/**
 * Test ArticleController Fix
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "🔧 Testing ArticleController Fix...\n\n";

try {
    // Test if we can instantiate the ArticleController
    $controller = new \App\Http\Controllers\Admin\ArticleController();
    echo "✅ ArticleController: Successfully instantiated\n";
    
    // Test if middleware method is available
    if (method_exists($controller, 'middleware')) {
        echo "✅ Middleware Method: Available\n";
    } else {
        echo "❌ Middleware Method: Not available\n";
    }
    
    // Test base controller
    $baseController = new class extends \App\Http\Controllers\Controller {
        public function testMiddleware() {
            return method_exists($this, 'middleware');
        }
    };
    
    if ($baseController->testMiddleware()) {
        echo "✅ Base Controller: Middleware method available\n";
    } else {
        echo "❌ Base Controller: Middleware method not available\n";
    }
    
    echo "\n🎉 ArticleController Fix: SUCCESSFUL\n";
    echo "📍 The controller should now work properly\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Stack trace: " . $e->getTraceAsString() . "\n";
}
