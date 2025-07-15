# 🚨 QUICK FIX untuk "Could not open input file"

## Jika error "Could not open input file: setup-lamdaku.php"

Jalankan ONE-LINER ini di server:

```bash
# SOLUSI 1: Emergency Setup (Copy-paste seluruh command ini)
cat > emergency-setup.php << 'EOF' && php emergency-setup.php
<?php
echo "=== EMERGENCY LAMDAKU SETUP ===\n";
if (!file_exists('.env')) {
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
MAIL_FROM_NAME="LAMDAKU CMS"';
    file_put_contents('.env', $env);
    echo "✅ .env created\n";
}
$dirs = ['storage/logs','storage/framework/cache/data','storage/framework/sessions','storage/framework/views','storage/app/public','storage/app/public/logos','bootstrap/cache'];
foreach($dirs as $dir) { if(!is_dir($dir)) { mkdir($dir, 0755, true); echo "✅ Created: $dir\n"; } }
chmod('storage', 0755); chmod('bootstrap/cache', 0755); chmod('.env', 0644);
if (!is_dir('public')) mkdir('public', 0755, true);
$htaccess = 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]';
file_put_contents('.htaccess', $htaccess);
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
file_put_contents('public/.htaccess', $publicHtaccess);
echo "✅ .htaccess files created\n";
echo "🎉 Emergency setup complete!\n";
echo "Next: Update DB_PASSWORD in .env, then run composer install\n";
?>
EOF
```

## ATAU Jika masih error, gunakan SOLUSI 2:

```bash
# SOLUSI 2: Re-clone repository dari awal
rm -rf * .*
git clone https://github.com/lipamitranusa/lamdakubackend.git .
ls -la setup-lamdaku.php
php setup-lamdaku.php
```

## Jika git clone juga error:

```bash
# SOLUSI 3: Manual minimal setup
echo 'APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE' > .env

mkdir -p storage/{logs,framework/{cache/data,sessions,views},app/public/logos} bootstrap/cache public
chmod -R 755 storage bootstrap/cache

echo 'RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]' > .htaccess

echo '<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>' > public/.htaccess

echo "Manual setup complete! Update .env DB_PASSWORD then run composer install"
```

## Setelah setup berhasil:

```bash
# 1. Update database password
nano .env
# Ganti YOUR_DATABASE_PASSWORD_HERE dengan password asli

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Generate key
php artisan key:generate

# 4. Run migrations
php artisan migrate --force

# 5. Create storage link
php artisan storage:link

# 6. Cache config
php artisan config:cache
```
