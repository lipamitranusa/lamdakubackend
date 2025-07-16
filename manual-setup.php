<?php
/**
 * Manual Setup Script untuk LAMDAKU CMS
 * Jalankan setelah upload file via FTP
 * Usage: php manual-setup.php
 */

echo "=== LAMDAKU CMS MANUAL SETUP ===\n\n";

echo "Current directory: " . getcwd() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check if this looks like a Laravel project
$requiredFiles = ['artisan', 'composer.json', 'app', 'bootstrap', 'config'];
$missingFiles = [];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $missingFiles[] = $file;
    }
}

if (!empty($missingFiles)) {
    echo "❌ ERROR: Missing required Laravel files:\n";
    foreach ($missingFiles as $file) {
        echo "   - $file\n";
    }
    echo "\nPlease upload all Laravel project files first!\n";
    echo "Download from: https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip\n";
    exit(1);
}

echo "✅ All required Laravel files found!\n\n";

// Check and fix artisan file
echo "\n🔧 Checking artisan file...\n";

if (!file_exists('artisan')) {
    echo "❌ artisan file not found! Attempting to create...\n";
    
    // Try to download artisan file
    $artisanContent = file_get_contents('https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan');
    if ($artisanContent) {
        file_put_contents('artisan', $artisanContent);
        chmod('artisan', 0755);
        echo "✅ artisan file downloaded and created\n";
    } else {
        // Create basic artisan file
        $artisanContent = '#!/usr/bin/env php
<?php

define(\'LARAVEL_START\', microtime(true));

require __DIR__.\'/vendor/autoload.php\';

$app = require_once __DIR__.\'/bootstrap/app.php\';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);';
        
        file_put_contents('artisan', $artisanContent);
        chmod('artisan', 0755);
        echo "✅ Basic artisan file created\n";
    }
} else {
    // Check if artisan is executable
    $perms = fileperms('artisan');
    if (!($perms & 0x0040)) { // Check if owner has execute permission
        chmod('artisan', 0755);
        echo "✅ artisan permissions fixed (755)\n";
    } else {
        echo "✅ artisan file exists and is executable\n";
    }
    
    // Check if artisan file is not empty and starts correctly
    $artisanContent = file_get_contents('artisan');
    if (empty($artisanContent) || strpos($artisanContent, '#!/usr/bin/env php') !== 0) {
        echo "⚠️ artisan file appears corrupted, attempting to fix...\n";
        
        // Re-download or recreate artisan
        $newArtisanContent = file_get_contents('https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan');
        if ($newArtisanContent) {
            file_put_contents('artisan', $newArtisanContent);
            chmod('artisan', 0755);
            echo "✅ artisan file fixed from repository\n";
        }
    }
}

// Set file permissions
echo "🔐 Setting file permissions...\n";

if (file_exists('artisan')) {
    chmod('artisan', 0755);
    echo "✅ artisan permissions set (755)\n";
}

// Set directory permissions
$directories = [
    'storage' => 0755,
    'bootstrap/cache' => 0755,
    'storage/logs' => 0755,
    'storage/framework/cache' => 0755,
    'storage/framework/sessions' => 0755,
    'storage/framework/views' => 0755,
    'storage/app/public' => 0755
];

foreach ($directories as $dir => $perm) {
    if (is_dir($dir)) {
        chmod($dir, $perm);
        // Set recursive permissions for storage
        if (strpos($dir, 'storage') === 0) {
            if (function_exists('exec')) {
                exec("find $dir -type d -exec chmod 755 {} \\; 2>/dev/null");
                exec("find $dir -type f -exec chmod 644 {} \\; 2>/dev/null");
            }
        }
        echo "✅ $dir permissions set ($perm)\n";
    }
}

// Create .env file
echo "\n⚙️ Setting up .env file...\n";

if (!file_exists('.env')) {
    if (file_exists('.env.production')) {
        copy('.env.production', '.env');
        echo "✅ .env created from .env.production\n";
    } elseif (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✅ .env created from .env.example\n";
    } else {
        // Create basic .env
        $env = 'APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@lamdaku.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lamdaku.com"
MAIL_FROM_NAME="LAMDAKU CMS"';
        
        file_put_contents('.env', $env);
        echo "✅ Basic .env created\n";
    }
    
    chmod('.env', 0644);
} else {
    echo "✅ .env file already exists\n";
}

// Create .htaccess files if missing
echo "\n🔗 Checking .htaccess files...\n";

if (!file_exists('.htaccess')) {
    $rootHtaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
    file_put_contents('.htaccess', $rootHtaccess);
    echo "✅ Root .htaccess created\n";
} else {
    echo "✅ Root .htaccess exists\n";
}

if (!file_exists('public/.htaccess')) {
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
} else {
    echo "✅ Public .htaccess exists\n";
}

// Test artisan
echo "\n🧪 Testing artisan...\n";
if (file_exists('vendor/autoload.php')) {
    $output = shell_exec('php artisan --version 2>&1');
    if ($output && strpos($output, 'Laravel') !== false) {
        echo "✅ Artisan is working!\n";
        echo "   Version: " . trim($output) . "\n";
    } else {
        echo "⚠️ Artisan test failed, but files are in place\n";
        echo "   Output: " . trim($output) . "\n";
    }
} else {
    echo "⚠️ vendor/autoload.php not found - need to run composer install\n";
}

// Check database connection
echo "\n🗃️ Testing database connection...\n";
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
    if (isset($env['DB_PASSWORD']) && $env['DB_PASSWORD'] !== 'YOUR_DATABASE_PASSWORD_HERE') {
        try {
            $pdo = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']}",
                $env['DB_USERNAME'],
                $env['DB_PASSWORD']
            );
            echo "✅ Database connection successful!\n";
        } catch (PDOException $e) {
            echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "⚠️ Database password not set in .env (still shows placeholder)\n";
    }
}

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 MANUAL SETUP COMPLETE!\n";
echo str_repeat("=", 50) . "\n\n";

echo "📋 NEXT STEPS:\n";
echo "1. ✏️  Update database password in .env file\n";
echo "2. 📦 Install dependencies: composer install --no-dev --optimize-autoloader\n";
echo "3. 🔑 Generate app key: php artisan key:generate\n";
echo "4. 🗃️  Run migrations: php artisan migrate --force\n";
echo "5. 🔗 Create storage link: php artisan storage:link\n";
echo "6. 💾 Cache config: php artisan config:cache\n";
echo "7. 🌐 Test website: https://lamdaku.com\n\n";

echo "🔧 TROUBLESHOOTING:\n";
echo "- If composer not available, upload vendor/ folder manually\n";
echo "- If permissions issues, use cPanel File Manager to set permissions\n";
echo "- Check error logs: tail -f storage/logs/laravel.log\n\n";

echo "📁 PROJECT STRUCTURE:\n";
$structure = [
    'artisan' => file_exists('artisan') ? '✅' : '❌',
    'composer.json' => file_exists('composer.json') ? '✅' : '❌',
    '.env' => file_exists('.env') ? '✅' : '❌',
    'app/' => is_dir('app') ? '✅' : '❌',
    'config/' => is_dir('config') ? '✅' : '❌',
    'database/' => is_dir('database') ? '✅' : '❌',
    'public/' => is_dir('public') ? '✅' : '❌',
    'storage/' => is_dir('storage') ? '✅' : '❌',
    'vendor/' => is_dir('vendor') ? '✅' : '❌ (run composer install)',
];

foreach ($structure as $item => $status) {
    echo "$status $item\n";
}

echo "\n✅ Setup script completed successfully!\n";
?>
