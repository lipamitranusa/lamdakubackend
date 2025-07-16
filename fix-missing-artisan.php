<?php
/**
 * Emergency Fix untuk Missing Artisan File
 * Usage: php fix-missing-artisan.php
 */

echo "🔧 EMERGENCY FIX: Missing Artisan File\n";
echo str_repeat("=", 50) . "\n";

// Configuration
$repoUrl = 'https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master';
$requiredFiles = [
    'artisan' => 'artisan',
    'composer.json' => 'composer.json',
    '.env.production' => '.env.production'
];

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

// Step 0: Check for lamdakubackend folder issue
log_info("Step 0: Checking for folder structure issues...");

if (is_dir('lamdakubackend') && !file_exists('artisan')) {
    log_warning("Found 'lamdakubackend' folder - git clone created subfolder");
    log_info("Moving files from lamdakubackend/ to current directory...");
    
    // Get list of files in lamdakubackend
    $files = glob('lamdakubackend/*');
    $hiddenFiles = glob('lamdakubackend/.*');
    
    // Move visible files
    foreach($files as $file) {
        $basename = basename($file);
        if (rename($file, $basename)) {
            log_info("Moved: $basename");
        }
    }
    
    // Move hidden files
    foreach($hiddenFiles as $file) {
        $basename = basename($file);
        if ($basename !== '.' && $basename !== '..') {
            if (rename($file, $basename)) {
                log_info("Moved hidden: $basename");
            }
        }
    }
    
    // Remove empty lamdakubackend folder
    if (rmdir('lamdakubackend')) {
        log_success("Removed empty lamdakubackend folder");
    }
    
    log_success("Files moved from lamdakubackend/ to current directory");
}

// Step 1: Diagnose current state
log_info("Step 1: Diagnosing current state...");
echo "📍 Current directory: " . getcwd() . "\n";

$currentFiles = glob('*');
$hiddenFiles = glob('.*');
$allFiles = array_merge($currentFiles, $hiddenFiles);

echo "📂 Current files:\n";
foreach($allFiles as $file) {
    if ($file !== '.' && $file !== '..') {
        if (is_dir($file)) {
            echo "  📁 $file/\n";
        } else {
            echo "  📄 $file\n";
        }
    }
}

// Step 2: Check Laravel files
log_info("Step 2: Checking Laravel files...");

$laravelFiles = ['artisan', 'composer.json', 'app', 'config', 'bootstrap', 'public'];
$missingFiles = [];

foreach($laravelFiles as $file) {
    if (file_exists($file)) {
        log_success("$file: Found");
    } else {
        log_error("$file: Missing");
        $missingFiles[] = $file;
    }
}

// Step 3: Fix missing artisan
if (!file_exists('artisan')) {
    log_info("Step 3: Fixing missing artisan file...");
    
    // Try to download artisan
    $artisanUrl = "$repoUrl/artisan";
    log_info("Downloading artisan from: $artisanUrl");
    
    $artisanContent = @file_get_contents($artisanUrl);
    if ($artisanContent !== false) {
        file_put_contents('artisan', $artisanContent);
        chmod('artisan', 0755);
        log_success("artisan downloaded and permissions set (755)");
    } else {
        log_warning("Failed to download artisan, creating basic version...");
        
        // Create basic artisan file
        $basicArtisan = '#!/usr/bin/env php
<?php

define("LARAVEL_START", microtime(true));

require __DIR__."/vendor/autoload.php";

$app = require_once __DIR__."/bootstrap/app.php";

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);';
        
        file_put_contents('artisan', $basicArtisan);
        chmod('artisan', 0755);
        log_success("Basic artisan created and permissions set");
    }
} else {
    log_info("Step 3: artisan file exists, setting permissions...");
    chmod('artisan', 0755);
    log_success("artisan permissions set (755)");
}

// Step 4: Download other missing essential files
if (!file_exists('composer.json')) {
    log_info("Step 4: Downloading composer.json...");
    $composerContent = @file_get_contents("$repoUrl/composer.json");
    if ($composerContent !== false) {
        file_put_contents('composer.json', $composerContent);
        log_success("composer.json downloaded");
    } else {
        log_warning("Failed to download composer.json");
    }
}

if (!file_exists('.env.production') && !file_exists('.env')) {
    log_info("Downloading .env.production...");
    $envContent = @file_get_contents("$repoUrl/.env.production");
    if ($envContent !== false) {
        file_put_contents('.env.production', $envContent);
        log_success(".env.production downloaded");
    }
}

// Step 5: Create basic directory structure if missing
log_info("Step 5: Creating basic directory structure...");

$requiredDirs = [
    'app/Http/Controllers',
    'app/Models', 
    'bootstrap/cache',
    'config',
    'database/migrations',
    'public',
    'resources/views',
    'routes',
    'storage/app/public',
    'storage/framework/cache/data',
    'storage/framework/sessions', 
    'storage/framework/views',
    'storage/logs'
];

foreach($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        log_success("Created directory: $dir");
    }
}

// Step 6: Set permissions
log_info("Step 6: Setting permissions...");

if (is_dir('storage')) {
    chmod('storage', 0755);
    system('find storage -type d -exec chmod 755 {} \; 2>/dev/null');
    system('find storage -type f -exec chmod 644 {} \; 2>/dev/null');
    log_success("storage/ permissions set");
}

if (is_dir('bootstrap/cache')) {
    chmod('bootstrap/cache', 0755);
    log_success("bootstrap/cache/ permissions set");
}

// Step 7: Create .env if needed
if (!file_exists('.env')) {
    if (file_exists('.env.production')) {
        copy('.env.production', '.env');
        log_success(".env created from .env.production");
    } else {
        log_info("Creating basic .env file...");
        $basicEnv = 'APP_NAME="LAMDAKU CMS"
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

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120';
        
        file_put_contents('.env', $basicEnv);
        log_success("Basic .env created");
    }
    chmod('.env', 0644);
}

// Step 8: Create .htaccess files
log_info("Step 8: Creating .htaccess files...");

if (!file_exists('.htaccess')) {
    $rootHtaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
    file_put_contents('.htaccess', $rootHtaccess);
    log_success("Root .htaccess created");
}

if (!file_exists('public/.htaccess')) {
    if (!is_dir('public')) {
        mkdir('public', 0755, true);
    }
    
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
    
    file_put_contents('public/.htaccess', $publicHtaccess);
    log_success("public/.htaccess created");
}

// Step 9: Test artisan
log_info("Step 9: Testing artisan...");

if (file_exists('artisan')) {
    $artisanCheck = shell_exec('ls -la artisan 2>&1');
    echo "📄 artisan file details:\n$artisanCheck\n";
    
    // Test artisan with detailed error checking
    log_info("Testing artisan command...");
    
    if (!file_exists('vendor/autoload.php')) {
        log_warning("vendor/autoload.php not found - Laravel dependencies missing");
        log_info("Attempting to install dependencies...");
        
        // Try composer first
        $composerTest = shell_exec('composer --version 2>&1');
        if (strpos($composerTest, 'Composer') !== false) {
            log_info("Composer found, installing dependencies...");
            $composerInstall = shell_exec('composer install --no-dev --optimize-autoloader 2>&1');
            echo "📦 Composer output: " . substr($composerInstall, 0, 200) . "...\n";
        } else {
            log_warning("Composer not available, trying alternative download...");
            log_info("Note: You may need to install dependencies manually");
        }
    }
    
    // Test artisan again
    $artisanTest = shell_exec('php artisan --version 2>&1');
    if ($artisanTest && strpos($artisanTest, 'Laravel') !== false) {
        log_success("artisan working: " . trim($artisanTest));
    } else {
        log_warning("artisan test failed");
        echo "🔍 Error details: " . trim($artisanTest) . "\n";
        
        // Additional debugging
        $phpTest = shell_exec('php -v 2>&1');
        echo "🐘 PHP version: " . trim(explode("\n", $phpTest)[0]) . "\n";
        
        if (!file_exists('bootstrap/app.php')) {
            log_warning("bootstrap/app.php missing - Laravel bootstrap file required");
        }
        
        if (!file_exists('config/app.php')) {
            log_warning("config/app.php missing - Laravel config required");
        }
        
        echo "💡 If artisan exists but doesn't work:\n";
        echo "   1. Check: composer install --no-dev\n";
        echo "   2. Check: php -d display_errors=1 artisan --version\n";
        echo "   3. Consider: Complete project re-download\n";
    }
} else {
    log_error("artisan file still missing after fix attempt");
}

// Step 10: Summary and next steps
echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 EMERGENCY FIX COMPLETED!\n";
echo str_repeat("=", 50) . "\n\n";

echo "📊 CURRENT STATUS:\n";
$statusFiles = ['artisan', 'composer.json', '.env', 'app', 'config', 'public', 'storage'];
foreach($statusFiles as $file) {
    $status = file_exists($file) ? '✅' : '❌';
    echo "  $status $file\n";
}

echo "\n📋 NEXT STEPS:\n";
if (count($missingFiles) > 3) {
    echo "  🚨 Many files missing - consider full project download:\n";
    echo "     git clone https://github.com/lipamitranusa/lamdakubackend.git .\n";
    echo "     OR download ZIP and extract\n";
} else {
    echo "  1. 🔧 Update DB_PASSWORD in .env file\n";
    echo "  2. 📦 Install dependencies: composer install --no-dev\n";
    echo "  3. 🔑 Generate key: php artisan key:generate\n";
    echo "  4. 🗃️  Run migrations: php artisan migrate\n";
    echo "  5. 🌐 Test website: https://lamdaku.com\n";
}

echo "\n🔧 TROUBLESHOOTING:\n";
echo "  • If 'chmod: cannot access artisan': File was created successfully\n";
echo "  • If artisan command fails: Check vendor/ folder exists\n";
echo "  • If website errors: Check .env database settings\n";
echo "  • For complete setup: See REDEPLOY_GUIDE.md\n";

log_success("Emergency fix script completed!");
?>
