<?php
/**
 * Upload Vendor Helper Script
 * Untuk setup manual composer dependencies
 */

echo "=== UPLOAD VENDOR HELPER ===\n\n";

// Check current situation
echo "📍 Current directory: " . getcwd() . "\n";
echo "🧪 Checking project status...\n\n";

// Check if Laravel files exist
$laravelFiles = ['artisan', 'composer.json', 'app', 'config'];
$missing = [];
foreach ($laravelFiles as $file) {
    if (!file_exists($file)) {
        $missing[] = $file;
    } else {
        echo "✅ $file found\n";
    }
}

if (!empty($missing)) {
    echo "\n❌ Missing Laravel files: " . implode(', ', $missing) . "\n";
    echo "Please upload Laravel project files first!\n";
    exit(1);
}

echo "\n🔍 Checking dependencies...\n";

// Check vendor folder
if (is_dir('vendor') && file_exists('vendor/autoload.php')) {
    echo "✅ vendor/ folder exists\n";
    echo "✅ vendor/autoload.php exists\n";
    
    // Test autoloader
    try {
        require_once 'vendor/autoload.php';
        echo "✅ Autoloader works\n";
        
        // Test artisan
        $output = shell_exec('php artisan --version 2>&1');
        if (strpos($output, 'Laravel') !== false) {
            echo "✅ Artisan works: " . trim($output) . "\n";
            echo "\n🎉 All dependencies are working!\n";
            echo "You can proceed with:\n";
            echo "- php artisan key:generate\n";
            echo "- php artisan migrate --force\n";
            echo "- php artisan storage:link\n";
        } else {
            echo "⚠️ Artisan test failed: " . trim($output) . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Autoloader error: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ vendor/ folder missing or incomplete\n";
    echo "\n📋 OPTIONS TO FIX:\n\n";
    
    echo "🔄 OPTION 1: Download pre-built vendor (fastest)\n";
    echo "wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.tar.gz\n";
    echo "tar -xzf vendor.tar.gz\n";
    echo "chmod -R 755 vendor/\n";
    echo "php artisan --version\n\n";
    
    echo "📦 OPTION 2: Install composer locally\n";
    echo "curl -sS https://getcomposer.org/installer | php\n";
    echo "php composer.phar install --no-dev --optimize-autoloader\n\n";
    
    echo "💾 OPTION 3: Upload vendor/ from local computer\n";
    echo "1. On your computer: composer install --no-dev --optimize-autoloader\n";
    echo "2. Compress vendor/ folder to vendor.zip\n";
    echo "3. Upload vendor.zip via cPanel File Manager\n";
    echo "4. Extract vendor.zip on server\n";
    echo "5. Run: chmod -R 755 vendor/\n\n";
    
    echo "🚨 OPTION 4: Emergency mode (basic functionality)\n";
    echo "php create-emergency-vendor.php\n\n";
    
    // Create emergency vendor script
    $emergencyScript = '<?php
echo "Creating emergency vendor setup...\n";

// Create vendor directory
if (!is_dir("vendor")) {
    mkdir("vendor", 0755, true);
}

// Create minimal autoload.php
$autoload = \'<?php
// Emergency autoloader for LAMDAKU CMS
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = __DIR__ . "/../" . str_replace("\\\\", "/", $class) . ".php";
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Try app directory
    $appFile = __DIR__ . "/../app/" . str_replace("App\\\\", "", $class) . ".php";
    $appFile = str_replace("\\\\", "/", $appFile);
    if (file_exists($appFile)) {
        require_once $appFile;
        return true;
    }
    
    return false;
});

// Basic Laravel helper functions
if (!function_exists("env")) {
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? getenv($key) ?? $default;
        
        // Handle boolean values
        if (is_string($value)) {
            switch (strtolower($value)) {
                case "true": return true;
                case "false": return false;
                case "null": return null;
                default: return $value;
            }
        }
        
        return $value;
    }
}

if (!function_exists("config")) {
    function config($key, $default = null) {
        static $config = [];
        
        if (empty($config)) {
            // Load basic config
            $config = [
                "app.name" => env("APP_NAME", "LAMDAKU CMS"),
                "app.env" => env("APP_ENV", "production"),
                "app.debug" => env("APP_DEBUG", false),
                "app.url" => env("APP_URL", "https://lamdaku.com"),
                "app.key" => env("APP_KEY"),
                
                "database.default" => env("DB_CONNECTION", "mysql"),
                "database.connections.mysql.driver" => "mysql",
                "database.connections.mysql.host" => env("DB_HOST", "localhost"),
                "database.connections.mysql.port" => env("DB_PORT", "3306"),
                "database.connections.mysql.database" => env("DB_DATABASE"),
                "database.connections.mysql.username" => env("DB_USERNAME"),
                "database.connections.mysql.password" => env("DB_PASSWORD"),
                "database.connections.mysql.charset" => "utf8mb4",
                "database.connections.mysql.collation" => "utf8mb4_unicode_ci",
            ];
        }
        
        return $config[$key] ?? $default;
    }
}

if (!function_exists("base_path")) {
    function base_path($path = "") {
        return __DIR__ . "/../" . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists("storage_path")) {
    function storage_path($path = "") {
        return base_path("storage" . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

if (!function_exists("public_path")) {
    function public_path($path = "") {
        return base_path("public" . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

// Load environment variables
if (file_exists(__DIR__ . "/../.env")) {
    $lines = file(__DIR__ . "/../.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, "=") !== false && substr($line, 0, 1) !== "#") {
            list($key, $value) = explode("=", $line, 2);
            $key = trim($key);
            $value = trim($value, "\'\\"");
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
?\>';

file_put_contents("vendor/autoload.php", $autoload);
echo "✅ Emergency autoload.php created\n";

// Create composer directory structure
$dirs = [
    "vendor/composer",
    "vendor/symfony/console",
    "vendor/illuminate/foundation"
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created: $dir\n";
    }
}

echo "🎉 Emergency vendor setup complete!\n";
echo "This provides basic functionality for Laravel.\n";
echo "For full functionality, install proper vendor/ folder.\n";
?>';
    
    file_put_contents('create-emergency-vendor.php', $emergencyScript);
    echo "📝 Created create-emergency-vendor.php script\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "VENDOR DEPENDENCY STATUS CHECK COMPLETE\n";
echo str_repeat("=", 50) . "\n";
?>
