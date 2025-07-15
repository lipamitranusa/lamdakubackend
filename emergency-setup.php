<?php
// EMERGENCY SETUP SCRIPT - Copy paste this if setup-lamdaku.php is missing
echo "=== EMERGENCY LAMDAKU CMS SETUP ===\n\n";

// 1. Create .env file
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
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
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
MAIL_FROM_NAME="${APP_NAME}"';

if (!file_exists('.env')) {
    file_put_contents('.env', $envContent);
    echo "✅ .env file created\n";
} else {
    echo "✅ .env file already exists\n";
}

// 2. Create directories
$directories = [
    'storage/logs',
    'storage/framework/cache/data', 
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/app/public',
    'storage/app/public/logos',
    'bootstrap/cache'
];

echo "\n📁 Creating directories...\n";
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created: {$dir}\n";
    }
}

// 3. Set permissions
chmod('storage', 0755);
chmod('bootstrap/cache', 0755);
if (file_exists('.env')) {
    chmod('.env', 0644);
}
echo "✅ Permissions set\n";

// 4. Create .htaccess
$rootHtaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
file_put_contents('.htaccess', $rootHtaccess);

$publicHtaccess = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>';

if (!is_dir('public')) {
    mkdir('public', 0755, true);
}
file_put_contents('public/.htaccess', $publicHtaccess);
echo "✅ .htaccess files created\n";

echo "\n🎉 Emergency setup completed!\n";
echo "\nNext steps:\n";
echo "1. Update DB_PASSWORD in .env file\n";
echo "2. Run: composer install --no-dev --optimize-autoloader\n";
echo "3. Run: php artisan key:generate\n";
echo "4. Run: php artisan migrate --force\n";
echo "5. Run: php artisan storage:link\n";
echo "6. Run: php artisan config:cache\n";
?>
