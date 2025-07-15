# 🚀 LAMDAKU CMS - PRODUCTION DEPLOYMENT GUIDE

## 📋 Overview
Complete step-by-step guide untuk deploy LAMDAKU CMS ke Hostinger production server.

## 🎯 Prerequisites
- ✅ Hostinger hosting account dengan PHP 8.1+
- ✅ Domain: lamdaku.com
- ✅ SSH access ke server
- ✅ Git repository dengan project code

---

## 📁 FILES YANG DIBUAT

### 1. setup-lamdaku.php
**Fungsi**: Main setup script yang membuat semua konfigurasi dasar
- Membuat file .env dengan template production
- Membuat direktori storage yang diperlukan
- Set file permissions
- Membuat .htaccess files
- Generate test page

### 2. setup-database.php
**Fungsi**: Database migration dan seeding script
- Test koneksi database
- Jalankan migrations
- Jalankan seeders
- Error handling untuk database issues

### 3. public/setup-status.php
**Fungsi**: Web-based status checker dan diagnostic tool
- Check semua requirements
- Display setup progress
- Show important URLs dan credentials
- Troubleshooting info

---

## 🔧 LANGKAH DEPLOYMENT

### STEP 1: Upload Project ke Server

```bash
# Method 1: Git Clone (Recommended)
ssh -p 65002 u329849080@37.44.245.60
cd /home/u329849080/domains/lamdaku.com/public_html/api

# Clone repository yang sudah updated
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Verify files exist
ls -la setup-lamdaku.php

# Method 2: Upload ZIP dan extract via File Manager
```

### STEP 2: Verify Files & Run Setup Script

```bash
# Check if setup files exist
ls -la setup-lamdaku.php setup-database.php

# If files don't exist, check current directory
pwd
ls -la

# If you're in wrong directory, navigate to correct one
cd /home/u329849080/domains/lamdaku.com/public_html/api

# Jalankan setup utama
php setup-lamdaku.php
```

**Jika error "Could not open input file":**
```bash
# Check if you're in the right directory
pwd

# List all PHP files to confirm
ls -la *.php

# If setup files are missing, re-clone the repository
rm -rf *
git clone https://github.com/lipamitranusa/lamdakubackend.git .
```

**Output yang diharapkan:**
```
=== LAMDAKU CMS SETUP - HOSTINGER ===

🔍 ENVIRONMENT CHECK
✅ .env file created
✅ Created: storage/logs
✅ Created: storage/framework/cache/data
...
🎉 SETUP COMPLETED IN 2.34 SECONDS
```

### STEP 3: Configure Database

```bash
# Edit file .env
nano .env

# Update baris ini:
DB_PASSWORD=YOUR_ACTUAL_DATABASE_PASSWORD
```

### STEP 4: Install Dependencies

```bash
# Install Composer packages
composer install --no-dev --optimize-autoloader

# Generate APP_KEY
php artisan key:generate
```

### STEP 5: Setup Database

```bash
# Jalankan database setup
php setup-database.php
```

**Output yang diharapkan:**
```
=== DATABASE SETUP FOR LAMDAKU CMS ===
✅ Database connection successful
🔄 Running migrations...
✅ Migrations completed successfully
🌱 Running seeders...
✅ Seeders completed successfully
```

### STEP 6: Final Configuration

```bash
# Create storage link
php artisan storage:link

# Cache configurations untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### STEP 7: Set Document Root

**Di Hostinger hPanel:**
1. Login ke hPanel
2. Websites → Manage
3. Advanced → Subdomain
4. Set document root ke: `public_html/api/public`

### STEP 8: Verification

Akses URL berikut untuk verify setup:
- **Status Check**: https://lamdaku.com/setup-status.php
- **Main Site**: https://lamdaku.com
- **Admin Panel**: https://lamdaku.com/admin/login
- **API Test**: https://lamdaku.com/api/v1/company-info

---

## 🔐 DEFAULT CREDENTIALS

### Admin Panel Access
```
URL: https://lamdaku.com/admin/login
Username: admin
Email: admin@lamdaku.com
Password: password123
```

### Database Configuration
```
Host: localhost
Database: u329849080_lamdaku_prod
Username: u329849080_lamdaku_user
Password: [Set in Hostinger database panel]
```

---

## 🚨 TROUBLESHOOTING

### Error: "Could not open input file: setup-lamdaku.php"

**Penyebab**: Setup files tidak ada di server

**Solusi**:
```bash
# 1. Check current directory
pwd
ls -la *.php

# 2. Ensure you're in the right directory
cd /home/u329849080/domains/lamdaku.com/public_html/api

# 3. If files are missing, re-clone repository
rm -rf * .*  # Be careful with this command!
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# 4. Alternative: Manual emergency setup
# Create file: emergency-setup.php and copy the emergency script content
php emergency-setup.php
```

**Emergency Manual Setup** (copy-paste this code):
```php
<?php
// Create .env file
$env = 'APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE';
file_put_contents('.env', $env);

// Create directories
$dirs = ['storage/logs','storage/framework/cache/data','storage/framework/sessions','storage/framework/views','storage/app/public','bootstrap/cache'];
foreach($dirs as $dir) if(!is_dir($dir)) mkdir($dir, 0755, true);

// Set permissions
chmod('storage', 0755); chmod('bootstrap/cache', 0755);
echo "Emergency setup complete!";
?>
```

### Error: "500 Internal Server Error"
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check permissions
chmod -R 755 storage bootstrap/cache
```

### Error: "Database connection failed"
```bash
# Verify database credentials
grep DB_ .env

# Test connection manually
php artisan tinker
>>> DB::connection()->getPdo();
```

### Error: "Class not found"
```bash
# Regenerate autoloader
composer dump-autoload --optimize
```

### Error: "APP_KEY not set"
```bash
# Generate new key
php artisan key:generate --force
```

---

## ✅ SUCCESS CHECKLIST

- [ ] ✅ Project uploaded to `/public_html/api/`
- [ ] ✅ `setup-lamdaku.php` berhasil dijalankan
- [ ] ✅ Database credentials configured di `.env`
- [ ] ✅ `composer install` completed
- [ ] ✅ `php artisan key:generate` executed
- [ ] ✅ `setup-database.php` successful
- [ ] ✅ `php artisan storage:link` created
- [ ] ✅ Configurations cached
- [ ] ✅ Document root set to `public`
- [ ] ✅ Website accessible: https://lamdaku.com
- [ ] ✅ Admin panel accessible: https://lamdaku.com/admin/login
- [ ] ✅ API responding: https://lamdaku.com/api/v1/company-info

---

## 🧹 POST-DEPLOYMENT CLEANUP

Setelah semua berjalan dengan baik:

```bash
# Remove setup files
rm setup-lamdaku.php
rm setup-database.php
rm public/setup-status.php

# Clear application cache
php artisan cache:clear
php artisan view:clear
```

---

## 🔄 UPDATE DEPLOYMENT

Untuk update kode di masa depan:

```bash
# Pull latest code
git pull origin main

# Update dependencies jika ada perubahan
composer install --no-dev --optimize-autoloader

# Run new migrations jika ada
php artisan migrate --force

# Clear dan rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📞 SUPPORT COMMANDS

### Check System Status
```bash
# Laravel status
php artisan about

# Check routes
php artisan route:list

# Check database
php artisan migrate:status
```

### Performance Optimization
```bash
# Optimize untuk production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Backup Database
```bash
# Create backup
mysqldump -u username -p database_name > backup.sql
```

---

## 🎯 FINAL NOTES

1. **Security**: Change default admin password setelah first login
2. **SSL**: Pastikan SSL certificate active untuk HTTPS
3. **Monitoring**: Setup log monitoring untuk production
4. **Backup**: Schedule regular database dan file backups
5. **Updates**: Keep Laravel dan dependencies updated

**🚀 LAMDAKU CMS siap untuk production use!**
