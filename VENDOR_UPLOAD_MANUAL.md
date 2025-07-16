# 📦 VENDOR UPLOAD MANUAL - LAMDAKU CMS

## 🚨 MASALAH: "composer install" tidak bisa dijalankan

**Solusi:** Upload folder vendor/ secara manual

---

## 🎯 METODE 1: Upload Vendor dari Komputer Local (RECOMMENDED)

### Step 1: Prepare di Komputer Local
```bash
# 1. Download project Laravel di komputer local
git clone https://github.com/lipamitranusa/lamdakubackend.git
cd lamdakubackend/

# 2. Install dependencies di local
composer install --no-dev --optimize-autoloader

# 3. Verify vendor folder created
ls -la vendor/
# Folder size: ~50-100MB
```

### Step 2: Compress Vendor Folder
```bash
# Windows (via Command Prompt atau PowerShell):
Compress-Archive -Path vendor -DestinationPath vendor.zip

# Linux/Mac:
tar -czf vendor.tar.gz vendor/

# Atau gunakan WinRAR/7zip untuk buat vendor.zip
```

### Step 3: Upload ke Server
1. **Login cPanel** Hostinger
2. **File Manager** → `domains/lamdaku.com/public_html/`
3. **Upload** file `vendor.zip` atau `vendor.tar.gz`
4. **Extract** archive (klik kanan → Extract)
5. **Delete** file archive setelah extract

### Step 4: Set Permissions
```bash
# Via cPanel Terminal atau SSH:
chmod -R 755 vendor/
```

### Step 5: Test
```bash
php artisan --version
# Output: Laravel Framework 8.x.x

ls -la vendor/autoload.php
# Output: -rw-r--r-- 1 user user 1234 vendor/autoload.php
```

---

## 🎯 METODE 2: Download Pre-built Vendor

### Option A: Wget Download
```bash
# Download vendor yang sudah di-build:
wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.tar.gz

# Extract:
tar -xzf vendor.tar.gz
chmod -R 755 vendor/
rm vendor.tar.gz

# Test:
php artisan --version
```

### Option B: Manual Download
1. **Download:** [vendor.tar.gz](https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.tar.gz)
2. **Upload** ke server via cPanel
3. **Extract** dan set permissions

---

## 🎯 METODE 3: Install Composer di Server

### Option A: Standard Install
```bash
# Download dan install Composer:
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Test:
composer --version

# Install dependencies:
cd /home/u329849080/domains/lamdaku.com/public_html
composer install --no-dev --optimize-autoloader
```

### Option B: Local Install (jika tidak ada root access)
```bash
# Install di home directory:
cd ~
curl -sS https://getcomposer.org/installer | php
mv composer.phar ~/composer
chmod +x ~/composer

# Use dengan full path:
cd /home/u329849080/domains/lamdaku.com/public_html
~/composer install --no-dev --optimize-autoloader
```

---

## 🚨 METODE 4: Emergency Mode (No Vendor)

**Jika semua metode di atas gagal:**

### Step 1: Create Emergency Script
```bash
# Upload dan jalankan:
php upload-vendor-helper.php

# Kemudian:
php create-emergency-vendor.php
```

### Step 2: Test Emergency Mode
```bash
# Test basic functionality:
php artisan --version
# Mungkin error, tapi website bisa jalan

# Test website:
curl https://lamdaku.com/api
```

---

## ✅ VERIFICATION CHECKLIST

### Cek Vendor Folder Structure:
```
vendor/
├── autoload.php (MUST EXIST)
├── composer/
├── symfony/
├── illuminate/
├── monolog/
└── ... (many other packages)
```

### Test Commands:
```bash
# 1. Basic test:
php artisan --version

# 2. List commands:
php artisan list

# 3. Check config:
php artisan config:show

# 4. Test database (after .env setup):
php artisan migrate:status
```

---

## 🚨 TROUBLESHOOTING

### "vendor/autoload.php not found"
```bash
# Check if vendor uploaded correctly:
ls -la vendor/autoload.php

# If missing, re-upload vendor folder
# Make sure to extract archive properly
```

### "Permission denied"
```bash
# Fix permissions:
chmod -R 755 vendor/
chmod 644 vendor/autoload.php
```

### "Class not found" errors
```bash
# Regenerate autoloader:
composer dump-autoload

# Or if no composer:
rm -rf vendor/composer/autoload_*
php upload-vendor-helper.php
```

### "Memory limit exceeded"
```bash
# Increase PHP memory:
php -d memory_limit=512M artisan --version

# Or edit .htaccess:
php_value memory_limit 512M
```

---

## 📊 SIZE REFERENCE

- **vendor.zip**: ~15-25MB (compressed)
- **vendor/ folder**: ~50-100MB (extracted)
- **Upload time**: 2-5 minutes (depending on internet)

---

## ⚡ QUICK COMMANDS

```bash
# Upload vendor dari local (one-liner):
composer install --no-dev && tar -czf vendor.tar.gz vendor/ && echo "Upload vendor.tar.gz to server"

# Server extract dan test (one-liner):
tar -xzf vendor.tar.gz && chmod -R 755 vendor/ && php artisan --version

# Emergency vendor (one-liner):
php upload-vendor-helper.php && php create-emergency-vendor.php && php artisan --version
```

**🎯 RECOMMENDED:** Gunakan **METODE 1** (Upload vendor dari komputer local) karena paling reliable dan mudah.
