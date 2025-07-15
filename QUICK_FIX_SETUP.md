# 🚨 QUICK FIX untuk Missing Files & Artisan Errors

## ⚠️ ARTISAN ERROR: "Could not open input file: artisan"

**Masalah:** File project Laravel tidak ada di server (artisan, composer.json, app/, dll.)

### SOLUSI LENGKAP:

```bash
# 1. Pastikan di direktori yang benar
cd /home/u329849080/domains/lamdaku.com/public_html/api

# 2. Hapus semua file lama (jika ada)
rm -rf * .*

# 3. Clone project dari GitHub
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# 4. Verifikasi file ada
ls -la composer.json artisan

# 5. Upgrade Composer ke versi 2 (PENTING!)
composer self-update

# 6. Install dependencies
composer install --no-dev --optimize-autoloader
```

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

# 🚨 QUICK FIX - FOKUS GIT ONLY

## ⚠️ MASALAH: File `artisan` Tidak Ada

**SOLUSI SEDERHANA: Gunakan Git Clone**

### 🎯 ONE-LINER SOLUTION (Copy-Paste di Server)

```bash
# COPY-PASTE COMMAND INI:
cd /home/u329849080/domains/lamdaku.com/public_html && rm -rf * .* 2>/dev/null; git clone https://github.com/lipamitranusa/lamdakubackend.git . && chmod +x artisan && cp .env.production .env 2>/dev/null || cp .env.example .env && chmod -R 755 storage bootstrap/cache && echo "✅ DONE! Update DB_PASSWORD in .env then run: composer install"
```

### 📝 LANGKAH MANUAL (Step by Step)

```bash
# 1. Masuk ke direktori website
cd /home/u329849080/domains/lamdaku.com/public_html

# 2. Bersihkan direktori (backup dulu jika perlu)
rm -rf * .*

# 3. Clone project dari GitHub
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# 4. Verifikasi file artisan ada
ls -la artisan
# Output: -rwxr-xr-x 1 user user 1733 artisan

# 5. Copy .env file
cp .env.production .env

# 6. Set permissions
chmod +x artisan
chmod -R 755 storage bootstrap/cache
chmod 644 .env

# 7. Test artisan
php artisan --version
# Output: Laravel Framework 8.x.x
```

### ⚙️ UPDATE DATABASE PASSWORD

```bash
# Edit .env file
nano .env

# Ganti baris ini:
# DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE
# Dengan password database asli Anda

# Save: Ctrl+X, Y, Enter
```

### 🚀 INSTALL & SETUP

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader

# 2. Generate key
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Storage link
php artisan storage:link

# 5. Cache config
php artisan config:cache
```

## 🚨 JIKA GIT TIDAK TERSEDIA

### 📁 METODE FTP UPLOAD (PALING MUDAH)

**Langkah-langkah upload manual via FTP/cPanel:**

#### 1. Download Project di Komputer
```
1. Buka: https://github.com/lipamitranusa/lamdakubackend
2. Klik "Code" → "Download ZIP"
3. Extract file ZIP di komputer
```

#### 2. Upload via cPanel File Manager
```
1. Login ke cPanel Hostinger
2. Buka "File Manager"
3. Masuk ke: domains/lamdaku.com/public_html/
4. Hapus semua file lama (atau backup ke folder lain)
5. Upload semua file dari folder lamdakubackend-main/
6. Extract jika upload dalam bentuk ZIP
```

#### 3. Set Permissions via cPanel
```
File artisan: 755
Folder storage/: 755 (recursive)
Folder bootstrap/cache/: 755 (recursive)
File .env: 644
```

#### 4. Setup via File Manager
```bash
# Rename .env.production menjadi .env
# Edit .env dan ganti DB_PASSWORD dengan password asli
# Upload dan jalankan manual-setup.php
```

#### 5. Jalankan Setup Script
```bash
# Via cPanel Terminal atau SSH:
php manual-setup.php

# Output:
# ✅ All required Laravel files found!
# ✅ artisan permissions set
# ✅ .env created
# 🎉 MANUAL SETUP COMPLETE!
```

### Alternatif 1: Download ZIP

```bash
# Download manual
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
unzip main.zip
mv lamdakubackend-main/* .
mv lamdakubackend-main/.* . 2>/dev/null || true
rm -rf lamdakubackend-main main.zip
ls -la artisan
```

### Alternatif 2: Upload via FTP

1. **Di komputer local:**
   - Download: https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
   - Extract ZIP file

2. **Upload ke server via cPanel File Manager:**
   - Upload semua file ke: `/home/u329849080/domains/lamdaku.com/public_html/`
   - Set permissions: chmod +x artisan

## ✅ VERIFIKASI SETUP BERHASIL

```bash
# 1. Cek artisan
php artisan --version

# 2. Cek struktur Laravel
ls -la app/ bootstrap/ config/ database/

# 3. Test basic
curl https://lamdaku.com/api 2>/dev/null || echo "Site not ready yet"
```

## 🎯 TROUBLESHOOTING

### Error: "git: command not found"
```bash
# Gunakan wget:
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip && unzip main.zip && mv lamdakubackend-main/* . && rm -rf main.zip lamdakubackend-main
```

### Error: "Permission denied"
```bash
chmod +x artisan
chmod -R 755 storage bootstrap/cache
```

### Error: "Directory not empty"
```bash
# Backup & clean:
mkdir backup_old && mv * backup_old/ 2>/dev/null; git clone https://github.com/lipamitranusa/lamdakubackend.git .
```

---

## 📋 CHECKLIST SETELAH GIT CLONE

- ✅ File `artisan` ada
- ✅ File `composer.json` ada  
- ✅ Folder `app/`, `config/`, `database/` ada
- ✅ File `.env` ada (copy dari .env.production)
- ⚙️ Update `DB_PASSWORD` di .env
- 📦 Run `composer install`
- 🚀 Setup Laravel (`migrate`, `storage:link`, etc.)

**🌐 Test URL:** https://lamdaku.com`
