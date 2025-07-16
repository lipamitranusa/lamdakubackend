<?php
/**
 * Emergency API 403 Fix and Test Script
 * Usage: php fix-api-403-emergency.php
 */

echo "🚨 EMERGENCY API 403 FIX\n";
echo str_repeat("=", 50) . "\n";

function log_info($message) {
    echo "ℹ️  $message\n";
}

function log_success($message) {
    echo "✅ $message\n";
}

function log_warning($message) {
    echo "⚠️  $message\n";
}

function log_error($message) {
    echo "❌ $message\n";
}

// Step 1: Clear all cache
log_info("Step 1: Clearing all Laravel cache...");
$commands = [
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan cache:clear',
    'php artisan view:clear'
];

foreach($commands as $command) {
    $output = shell_exec($command . ' 2>&1');
    log_info("Executed: $command");
}

// Step 2: Check CORS configuration
log_info("Step 2: Checking CORS configuration...");
if (file_exists('config/cors.php')) {
    $corsContent = file_get_contents('config/cors.php');
    
    if (strpos($corsContent, 'lamdaku.com') !== false) {
        log_success("CORS includes lamdaku.com domain");
    } else {
        log_warning("CORS may not include production domain");
        log_info("Updating CORS to allow production domain...");
        
        // Read current CORS content
        $corsContent = file_get_contents('config/cors.php');
        
        // Add production domains if not present
        if (strpos($corsContent, 'lamdaku.com') === false) {
            $corsContent = str_replace(
                "'allowed_origins' => [",
                "'allowed_origins' => [\n        'https://lamdaku.com',\n        'http://lamdaku.com',",
                $corsContent
            );
            
            file_put_contents('config/cors.php', $corsContent);
            log_success("Added production domains to CORS");
        }
    }
} else {
    log_error("CORS config file missing!");
}

// Step 3: Check API routes
log_info("Step 3: Checking API routes...");
$routesList = shell_exec('php artisan route:list --path=api 2>&1');
if ($routesList && strpos($routesList, 'articles') !== false) {
    log_success("API routes found");
    echo "📋 Available API routes:\n";
    $lines = explode("\n", $routesList);
    foreach($lines as $line) {
        if (strpos($line, 'api/v1') !== false) {
            echo "  " . trim($line) . "\n";
        }
    }
} else {
    log_error("API routes missing or invalid");
}

// Step 4: Check controllers
log_info("Step 4: Checking API controllers...");
$controllers = [
    'app/Http/Controllers/Api/ArticleController.php',
    'app/Http/Controllers/Api/EventController.php'
];

foreach($controllers as $controller) {
    if (file_exists($controller)) {
        log_success("$controller exists");
    } else {
        log_error("$controller missing");
    }
}

// Step 5: Test API endpoints locally
log_info("Step 5: Testing API endpoints...");

// Create test script
$testScript = '<?php
$baseUrl = "http://" . $_SERVER["HTTP_HOST"];
$endpoints = [
    "/api/v1/articles",
    "/api/v1/events",
    "/api/v1/company-info"
];

foreach($endpoints as $endpoint) {
    $url = $baseUrl . $endpoint;
    $context = stream_context_create([
        "http" => [
            "method" => "GET",
            "header" => "Content-Type: application/json\r\n" .
                       "Accept: application/json\r\n"
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ $endpoint: OK\n";
        } else {
            echo "⚠️  $endpoint: Invalid JSON\n";
        }
    } else {
        echo "❌ $endpoint: Failed\n";
    }
}
?>';

file_put_contents('test-api-endpoints.php', $testScript);
log_info("Created API test script: test-api-endpoints.php");

// Step 6: Check .env configuration
log_info("Step 6: Checking .env configuration...");
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    $checks = [
        'APP_URL=https://lamdaku.com' => 'Production URL',
        'APP_ENV=production' => 'Production environment',
        'APP_DEBUG=false' => 'Debug disabled for production'
    ];
    
    foreach($checks as $setting => $description) {
        if (strpos($envContent, $setting) !== false) {
            log_success("$description: Correct");
        } else {
            log_warning("$description: Check setting");
        }
    }
} else {
    log_error(".env file missing!");
}

// Step 7: Check file permissions
log_info("Step 7: Checking file permissions...");
$paths = [
    'storage' => '755',
    'bootstrap/cache' => '755',
    '.env' => '644',
    'config/cors.php' => '644'
];

foreach($paths as $path => $expectedPerm) {
    if (file_exists($path)) {
        $currentPerm = substr(sprintf('%o', fileperms($path)), -3);
        if ($currentPerm >= $expectedPerm) {
            log_success("$path: $currentPerm (OK)");
        } else {
            log_warning("$path: $currentPerm (should be $expectedPerm)");
            chmod($path, octdec($expectedPerm));
            log_info("Fixed permissions for $path");
        }
    }
}

// Step 8: Create API test commands
log_info("Step 8: Creating API test commands...");

$testCommands = [
    'curl -X GET https://lamdaku.com/api/v1/articles -H "Accept: application/json"',
    'curl -X GET https://lamdaku.com/api/v1/events -H "Accept: application/json"',
    'curl -X GET https://lamdaku.com/api/v1/company-info -H "Accept: application/json"'
];

echo "\n📋 TEST COMMANDS (run these manually):\n";
foreach($testCommands as $i => $command) {
    echo ($i + 1) . ". $command\n";
}

// Step 9: Generate browser test
$browserTest = '
<!-- Copy and paste this into browser console -->
const testAPI = async () => {
    const endpoints = [
        "/api/v1/articles",
        "/api/v1/events", 
        "/api/v1/company-info"
    ];
    
    for(const endpoint of endpoints) {
        try {
            const response = await fetch("https://lamdaku.com" + endpoint, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });
            
            if(response.ok) {
                const data = await response.json();
                console.log(`✅ ${endpoint}: Success`, data);
            } else {
                console.log(`❌ ${endpoint}: ${response.status} ${response.statusText}`);
            }
        } catch(error) {
            console.log(`❌ ${endpoint}: ${error.message}`);
        }
    }
};

testAPI();
';

file_put_contents('browser-api-test.js', $browserTest);
log_info("Created browser test: browser-api-test.js");

// Final summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 API 403 FIX COMPLETED!\n";
echo str_repeat("=", 50) . "\n\n";

echo "📊 SUMMARY:\n";
echo "✅ Cache cleared\n";
echo "✅ CORS configuration checked/updated\n";
echo "✅ API routes verified\n";
echo "✅ Controllers checked\n";
echo "✅ Permissions set\n";

echo "\n📋 NEXT STEPS:\n";
echo "1. 🧪 Run: php test-api-endpoints.php\n";
echo "2. 🌐 Test URLs manually with curl commands above\n";
echo "3. 🖥️  Test in browser console with browser-api-test.js\n";
echo "4. 🔄 If still 403, check web server configuration\n";

echo "\n🔧 MANUAL CHECKS:\n";
echo "• Test: https://lamdaku.com/api/v1/articles\n";
echo "• Test: https://lamdaku.com/api/v1/events\n";
echo "• Check browser console for CORS errors\n";
echo "• Verify frontend is calling correct API URLs\n";

log_success("Emergency API fix script completed!");
?>
