<?php
/**
 * Emergency Project Setup for Hostinger
 * Handles missing composer.json and project files
 */

echo "=== EMERGENCY PROJECT SETUP ===\n\n";

// Function to check if we're in the right directory
function checkProjectStructure() {
    $requiredFiles = ['composer.json', 'artisan', 'app'];
    $missingFiles = [];
    
    foreach($requiredFiles as $file) {
        if(!file_exists($file)) {
            $missingFiles[] = $file;
        }
    }
    
    return $missingFiles;
}

// Function to create directory structure
function createDirectoryStructure() {
    $dirs = [
        'app/Http/Controllers/Admin',
        'app/Http/Controllers/API',
        'app/Models',
        'bootstrap/cache',
        'config',
        'database/migrations',
        'database/seeders',
        'public/assets',
        'public/uploads',
        'resources/views/admin',
        'resources/views/layouts',
        'routes',
        'storage/app/public',
        'storage/framework/cache/data',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs'
    ];

    echo "Creating directory structure...\n";
    foreach($dirs as $dir) {
        if(!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "✅ Created: $dir\n";
        }
    }
}

// Function to create basic Laravel files
function createBasicFiles() {
    echo "\nCreating basic Laravel files...\n";
    
    // Create basic composer.json
    $composer = [
        'name' => 'lamdaku/cms-backend',
        'type' => 'project',
        'description' => 'LAMDAKU CMS Backend',
        'keywords' => ['framework', 'laravel'],
        'license' => 'MIT',
        'require' => [
            'php' => '^8.0',
            'fruitcake/laravel-cors' => '^2.0',
            'guzzlehttp/guzzle' => '^7.0.1',
            'laravel/framework' => '^8.75',
            'laravel/sanctum' => '^2.11',
            'laravel/tinker' => '^2.5'
        ],
        'require-dev' => [
            'facade/ignition' => '^2.5',
            'fakerphp/faker' => '^1.9.1',
            'laravel/sail' => '^1.0.1',
            'mockery/mockery' => '^1.4.4',
            'nunomaduro/collision' => '^5.10',
            'phpunit/phpunit' => '^9.5.10'
        ],
        'autoload' => [
            'psr-4' => [
                'App\\' => 'app/',
                'Database\\Factories\\' => 'database/factories/',
                'Database\\Seeders\\' => 'database/seeders/'
            ]
        ],
        'autoload-dev' => [
            'psr-4' => [
                'Tests\\' => 'tests/'
            ]
        ],
        'scripts' => [
            'post-autoload-dump' => [
                'Illuminate\\Foundation\\ComposerScripts::postAutoloadDump',
                '@php artisan package:discover --ansi'
            ],
            'post-update-cmd' => [
                '@php artisan vendor:publish --tag=laravel-assets --ansi'
            ],
            'post-root-package-install' => [
                '@php -r "file_exists(\'.env\') || copy(\'.env.example\', \'.env\');"'
            ],
            'post-create-project-cmd' => [
                '@php artisan key:generate --ansi'
            ]
        ],
        'extra' => [
            'laravel' => [
                'dont-discover' => []
            ]
        ],
        'config' => [
            'optimize-autoloader' => true,
            'preferred-install' => 'dist',
            'sort-packages' => true
        ],
        'minimum-stability' => 'dev',
        'prefer-stable' => true
    ];
    
    file_put_contents('composer.json', json_encode($composer, JSON_PRETTY_PRINT));
    echo "✅ Created composer.json\n";
    
    // Create artisan
    $artisan = '#!/usr/bin/env php
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
    
    file_put_contents('artisan', $artisan);
    chmod('artisan', 0755);
    echo "✅ Created artisan\n";
    
    // Create basic .env
    $env = 'APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=base64:' . base64_encode(random_bytes(32)) . '
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

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"';

    file_put_contents('.env', $env);
    echo "✅ Created .env\n";
    
    // Create public/index.php
    $publicIndex = '<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define("LARAVEL_START", microtime(true));

if (file_exists(__DIR__."/../storage/framework/maintenance.php")) {
    require __DIR__."/../storage/framework/maintenance.php";
}

require __DIR__."/../vendor/autoload.php";

$app = require_once __DIR__."/../bootstrap/app.php";

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);';

    file_put_contents('public/index.php', $publicIndex);
    echo "✅ Created public/index.php\n";
    
    // Create .htaccess files
    $rootHtaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
    file_put_contents('.htaccess', $rootHtaccess);
    
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
    echo "✅ Created .htaccess files\n";
}

// Function to set permissions
function setPermissions() {
    echo "\nSetting permissions...\n";
    
    if(is_dir('storage')) {
        chmod('storage', 0755);
        system('chmod -R 755 storage');
        echo "✅ Set storage permissions\n";
    }
    
    if(is_dir('bootstrap/cache')) {
        chmod('bootstrap/cache', 0755);
        echo "✅ Set bootstrap/cache permissions\n";
    }
}

// Main execution
echo "Current directory: " . getcwd() . "\n";
echo "Checking project structure...\n";

$missingFiles = checkProjectStructure();

if(!empty($missingFiles)) {
    echo "❌ Missing files: " . implode(', ', $missingFiles) . "\n";
    echo "This appears to be an empty directory or incomplete project.\n\n";
    
    $choice = readline("Do you want to create basic Laravel structure? (y/n): ");
    
    if(strtolower($choice) === 'y') {
        createDirectoryStructure();
        createBasicFiles();
        setPermissions();
        
        echo "\n=== BASIC STRUCTURE CREATED ===\n";
        echo "✅ Basic Laravel project structure has been created\n";
        echo "\n📋 NEXT STEPS:\n";
        echo "1. Update DB_PASSWORD in .env file\n";
        echo "2. Run: composer install --no-dev --optimize-autoloader\n";
        echo "3. Run: php artisan key:generate\n";
        echo "4. Upload your actual project files or clone from GitHub\n";
        echo "5. Run: php setup-lamdaku.php\n";
        
        echo "\n🔧 ALTERNATIVE - Clone from GitHub:\n";
        echo "git clone https://github.com/lipamitranusa/lamdakubackend.git .\n";
        echo "composer install --no-dev --optimize-autoloader\n";
        echo "php setup-lamdaku.php\n";
    }
} else {
    echo "✅ All required files found. Project structure looks good!\n";
    echo "You can proceed with:\n";
    echo "1. composer install --no-dev --optimize-autoloader\n";
    echo "2. php setup-lamdaku.php\n";
}

echo "\n=== Emergency Setup Complete ===\n";
?>
