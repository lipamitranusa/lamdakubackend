<?php
echo "=== LAMDAKU CMS SETUP - HOSTINGER ===\n\n";

$startTime = microtime(true);

// 1. Environment Check
echo "🔍 ENVIRONMENT CHECK\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Directory: " . getcwd() . "\n";
echo "User: " . get_current_user() . "\n\n";

// 2. Create .env file
echo "📝 CREATING .ENV FILE\n";
$envContent = 'APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku
DB_PASSWORD=Lamdaku25$

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@lamdaku.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lamdaku.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"';

file_put_contents('.env', $envContent);
echo "✅ .env file created\n\n";

// 3. Create required directories
echo "📁 CREATING DIRECTORIES\n";
$directories = [
    'storage/logs',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/app/public',
    'storage/app/public/logos',
    'bootstrap/cache',
    'public/storage'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created: {$dir}\n";
        } else {
            echo "❌ Failed to create: {$dir}\n";
        }
    } else {
        echo "✅ Exists: {$dir}\n";
    }
}

// 4. Set permissions
echo "\n🔒 SETTING PERMISSIONS\n";
$permissionPaths = [
    'storage' => 0755,
    'bootstrap/cache' => 0755,
    '.env' => 0644
];

foreach ($permissionPaths as $path => $permission) {
    if (file_exists($path)) {
        chmod($path, $permission);
        echo "✅ Set {$path} to " . decoct($permission) . "\n";
    }
}

// 5. Create .htaccess files
echo "\n🌐 CREATING .HTACCESS FILES\n";

// Root .htaccess
$rootHtaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
file_put_contents('.htaccess', $rootHtaccess);
echo "✅ Root .htaccess created\n";

// Public .htaccess
$publicHtaccess = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>';

if (!is_dir('public')) {
    mkdir('public', 0755, true);
}
file_put_contents('public/.htaccess', $publicHtaccess);
echo "✅ Public .htaccess created\n";

// 6. Create test page
echo "\n🧪 CREATING TEST PAGE\n";
$testPage = '<!DOCTYPE html>
<html>
<head>
    <title>LAMDAKU CMS - Setup Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <h1>🚀 LAMDAKU CMS - Setup Status</h1>
    
    <?php
    echo "<div class=\"status success\">✅ PHP Version: " . phpversion() . "</div>";
    echo "<div class=\"status success\">✅ Current Time: " . date("Y-m-d H:i:s") . "</div>";
    echo "<div class=\"status success\">✅ Server Software: " . $_SERVER["SERVER_SOFTWARE"] . "</div>";
    
    // Check .env
    if (file_exists("../.env")) {
        echo "<div class=\"status success\">✅ .env file exists</div>";
    } else {
        echo "<div class=\"status error\">❌ .env file missing</div>";
    }
    
    // Check vendor
    if (is_dir("../vendor")) {
        echo "<div class=\"status success\">✅ Vendor directory exists</div>";
    } else {
        echo "<div class=\"status error\">❌ Vendor directory missing - Run: composer install</div>";
    }
    
    // Check storage
    if (is_dir("../storage") && is_writable("../storage")) {
        echo "<div class=\"status success\">✅ Storage directory writable</div>";
    } else {
        echo "<div class=\"status error\">❌ Storage directory not writable</div>";
    }
    
    // Check Laravel
    try {
        if (file_exists("../vendor/autoload.php")) {
            require_once "../vendor/autoload.php";
            if (file_exists("../bootstrap/app.php")) {
                $app = require_once "../bootstrap/app.php";
                echo "<div class=\"status success\">✅ Laravel can be loaded</div>";
                
                try {
                    $kernel = $app->make("Illuminate\\Contracts\\Console\\Kernel");
                    $kernel->bootstrap();
                    echo "<div class=\"status success\">✅ Laravel bootstrap successful</div>";
                } catch (Exception $e) {
                    echo "<div class=\"status error\">❌ Laravel bootstrap failed: " . $e->getMessage() . "</div>";
                }
            }
        }
    } catch (Exception $e) {
        echo "<div class=\"status error\">❌ Laravel error: " . $e->getMessage() . "</div>";
    }
    ?>
    
    <h2>📋 Next Steps:</h2>
    <ol>
        <li>Update database credentials in .env file</li>
        <li>Run: <code>composer install --no-dev --optimize-autoloader</code></li>
        <li>Run: <code>php artisan key:generate</code></li>
        <li>Run: <code>php artisan migrate</code></li>
        <li>Run: <code>php artisan db:seed</code></li>
        <li>Run: <code>php artisan storage:link</code></li>
        <li>Run: <code>php artisan config:cache</code></li>
    </ol>
</body>
</html>';

file_put_contents('public/setup-status.php', $testPage);
echo "✅ Test page created at: /public/setup-status.php\n";

// 7. Create database setup script
echo "\n🗄️ CREATING DATABASE SETUP SCRIPT\n";
$dbSetupScript = '<?php
echo "=== DATABASE SETUP FOR LAMDAKU CMS ===\\n\\n";

// Check if .env exists and has DB credentials
if (!file_exists(".env")) {
    echo "❌ .env file not found!\\n";
    exit(1);
}

$env = file_get_contents(".env");
if (strpos($env, "YOUR_DATABASE_PASSWORD_HERE") !== false) {
    echo "❌ Please update database password in .env file first!\\n";
    echo "Update this line: DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE\\n";
    exit(1);
}

// Bootstrap Laravel
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\\Contracts\\Console\\Kernel");
$kernel->bootstrap();

try {
    // Test database connection
    $pdo = $app->make("db")->connection()->getPdo();
    echo "✅ Database connection successful\\n";
    
    // Run migrations
    echo "\\n🔄 Running migrations...\\n";
    $output = [];
    $return_var = 0;
    exec("php artisan migrate --force 2>&1", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ Migrations completed successfully\\n";
        foreach ($output as $line) {
            echo "   {$line}\\n";
        }
        
        // Run seeders
        echo "\\n🌱 Running seeders...\\n";
        exec("php artisan db:seed --force 2>&1", $output, $return_var);
        
        if ($return_var === 0) {
            echo "✅ Seeders completed successfully\\n";
        } else {
            echo "⚠️ Seeders completed with warnings\\n";
        }
        
    } else {
        echo "❌ Migration failed\\n";
        foreach ($output as $line) {
            echo "   {$line}\\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\\n";
    echo "\\nPlease check your database credentials in .env file\\n";
}
?>';

file_put_contents('setup-database.php', $dbSetupScript);
echo "✅ Database setup script created\n";

// 8. Summary
$endTime = microtime(true);
$duration = round($endTime - $startTime, 2);

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 SETUP COMPLETED IN {$duration} SECONDS\n";
echo str_repeat("=", 50) . "\n\n";

echo "📋 NEXT STEPS:\n";
echo "1. 🔧 Update database credentials in .env file\n";
echo "2. 📦 Run: composer install --no-dev --optimize-autoloader\n";
echo "3. 🔑 Run: php artisan key:generate\n";
echo "4. 🗄️ Run: php setup-database.php\n";
echo "5. 🔗 Run: php artisan storage:link\n";
echo "6. ⚡ Run: php artisan config:cache\n";
echo "7. 🌐 Test: https://lamdaku.com/setup-status.php\n";
echo "8. 🚀 Access admin: https://lamdaku.com/admin/login\n\n";

echo "🔗 IMPORTANT URLS:\n";
echo "   Setup Status: https://lamdaku.com/setup-status.php\n";
echo "   Admin Panel: https://lamdaku.com/admin/login\n";
echo "   API Docs: https://lamdaku.com/api/v1/\n\n";

echo "⚠️  REMEMBER TO:\n";
echo "   - Update DB_PASSWORD in .env\n";
echo "   - Set document root to: public_html/api/public\n";
echo "   - Delete setup files after completion\n";
?>
