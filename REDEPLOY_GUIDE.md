# 🚀 DEPLOYMENT ULANG LAMDAKU CMS BACKEND

## 🎯 PANDUAN LENGKAP DEPLOY ULANG (Fresh Installation)

**Situasi:** Deploy ulang aplikasi backend dari awal dengan clean installation

---

## 📋 PERSIAPAN DEPLOYMENT

### 1. Backup Data Lama (PENTING!)
```bash
# Backup database (via cPanel phpMyAdmin atau command line)
mysqldump -u u329849080_lamdaku_user -p u329849080_lamdaku_prod > backup_$(date +%Y%m%d).sql

# Backup file penting (jika ada)
mkdir backup_old_$(date +%Y%m%d)
cp -r public_html/* backup_old_$(date +%Y%m%d)/
```

### 2. Informasi yang Dibutuhkan
- ✅ Database Host: `localhost`
- ✅ Database Name: `u329849080_lamdaku_prod`
- ✅ Database User: `u329849080_lamdaku_user`
- ✅ Database Password: `[password_anda]`
- ✅ Domain: `https://lamdaku.com`
- ✅ Server Path: `/home/u329849080/domains/lamdaku.com/public_html/`

---

## 🔥 METODE 1: DEPLOYMENT BERSIH (RECOMMENDED)

### Step 1: Bersihkan Server
```bash
# Masuk ke direktori website
cd /home/u329849080/domains/lamdaku.com/public_html/

# Backup semua file lama
mkdir -p ../backup_$(date +%Y%m%d_%H%M%S)
mv * ../backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true
mv .* ../backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true

# Verifikasi direktori kosong
ls -la
```

### Step 2: Download Project Terbaru
```bash
# Clone project terbaru
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Verifikasi file ada
ls -la artisan composer.json .env.production

# Set permissions dasar
chmod +x artisan
chmod -R 755 storage bootstrap/cache
```

### Step 3: Setup Environment
```bash
# Copy .env file
cp .env.production .env

# Edit .env (update database password)
nano .env
# Ganti: DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE
# Dengan: DB_PASSWORD=password_database_asli_anda
```

### Step 4: Install Dependencies
```bash
# Install via Composer (jika tersedia)
composer install --no-dev --optimize-autoloader

# ATAU upload vendor manual (jika composer tidak ada)
# Download vendor.tar.gz dari backup atau build di local
# Upload dan extract ke server
```

### Step 5: Laravel Setup
```bash
# Generate application key
php artisan key:generate

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations (fresh database)
php artisan migrate:fresh --seed --force

# Create storage link
php artisan storage:link

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🚀 METODE 2: ONE-LINER DEPLOYMENT

```bash
# COPY-PASTE COMMAND INI:
cd /home/u329849080/domains/lamdaku.com/public_html && mkdir -p ../backup_$(date +%Y%m%d_%H%M%S) && mv * ../backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null; mv .* ../backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null; git clone https://github.com/lipamitranusa/lamdakubackend.git . && chmod +x artisan && chmod -R 755 storage bootstrap/cache && cp .env.production .env && echo "✅ Project downloaded! Update .env then run setup."
```

---

## 📁 METODE 3: DEPLOYMENT VIA FTP/cPanel

### Step 1: Download Project
1. **Download:** https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
2. **Extract** di komputer: `lamdakubackend-main/`

### Step 2: Backup & Clean Server
1. **Login cPanel** → **File Manager**
2. **Masuk:** `domains/lamdaku.com/public_html/`
3. **Select All** → **Create backup folder** → **Move files to backup**
4. **Verify** direktori kosong

### Step 3: Upload Project
1. **Upload** semua file dari `lamdakubackend-main/`
2. **Extract** jika upload dalam bentuk ZIP
3. **Set permissions:**
   - `artisan`: 755
   - `storage/`: 755 (recursive)
   - `bootstrap/cache/`: 755 (recursive)

### Step 4: Setup via cPanel
1. **Rename** `.env.production` → `.env`
2. **Edit** `.env` → Update `DB_PASSWORD`
3. **Upload & run** `manual-setup.php`

---

## ⚡ DEPLOYMENT SCRIPT OTOMATIS

Mari saya buatkan script deployment otomatis:

```bash
# Create deployment script
cat > deploy-fresh.php << 'EOF'
<?php
echo "=== LAMDAKU CMS FRESH DEPLOYMENT ===\n";

// Backup existing files
$backupDir = '../backup_' . date('Ymd_His');
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
    echo "✅ Backup directory created: $backupDir\n";
}

// Move existing files to backup
$files = glob('*');
foreach ($files as $file) {
    if ($file !== 'deploy-fresh.php') {
        rename($file, "$backupDir/$file");
    }
}
echo "✅ Files backed up\n";

// Download and extract project
echo "📥 Downloading latest project...\n";
system('git clone https://github.com/lipamitranusa/lamdakubackend.git .');

// Set permissions
chmod('artisan', 0755);
system('chmod -R 755 storage bootstrap/cache');
echo "✅ Permissions set\n";

// Setup .env
if (file_exists('.env.production')) {
    copy('.env.production', '.env');
    echo "✅ .env created\n";
}

echo "🎉 Fresh deployment complete!\n";
echo "Next steps:\n";
echo "1. Update DB_PASSWORD in .env\n";
echo "2. Run: composer install --no-dev\n";
echo "3. Run: php artisan migrate:fresh --seed\n";
?>
EOF

# Run deployment
php deploy-fresh.php
```

---

## 🗃️ DATABASE SETUP

### Fresh Database Setup
```bash
# Drop all tables dan recreate
php artisan migrate:fresh --force

# Seed data (users, organizational structure, etc.)
php artisan db:seed --force

# Create admin user
php artisan make:command CreateAdminUser
php artisan app:create-admin-user
```

### Restore Data Lama (Optional)
```bash
# Restore dari backup
mysql -u u329849080_lamdaku_user -p u329849080_lamdaku_prod < backup_20250716.sql

# Atau selective restore
# Export specific tables dari backup dan import
```

---

## ✅ POST-DEPLOYMENT VERIFICATION

### 1. Test Basic Functionality
```bash
# Test artisan
php artisan --version

# Test database connection
php artisan migrate:status

# Test cache
php artisan config:show app.name
```

### 2. Test Web Endpoints
```bash
# API status
curl https://lamdaku.com/api/status

# Basic API
curl https://lamdaku.com/api

# Admin page (via browser)
# https://lamdaku.com/admin
```

### 3. Check File Structure
```bash
# Verify important files
ls -la artisan composer.json .env
ls -la app/ config/ database/ public/ resources/
ls -la storage/logs/ bootstrap/cache/
```

### 4. Check Permissions
```bash
# File permissions
ls -la artisan          # Should be: -rwxr-xr-x
ls -ld storage/         # Should be: drwxr-xr-x
ls -ld bootstrap/cache/ # Should be: drwxr-xr-x
```

---

## 🚨 TROUBLESHOOTING DEPLOYMENT

### Error: "git command not found"
```bash
# Use wget instead:
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
unzip main.zip
mv lamdakubackend-main/* .
rm -rf lamdakubackend-main main.zip
```

### Error: "composer command not found"
```bash
# Upload vendor manually:
# 1. Build vendor di local: composer install --no-dev
# 2. Compress: tar -czf vendor.tar.gz vendor/
# 3. Upload ke server
# 4. Extract: tar -xzf vendor.tar.gz
```

### Error: "Permission denied"
```bash
# Fix permissions:
chmod +x artisan
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### Error: "Database connection failed"
```bash
# Check .env settings:
cat .env | grep DB_
# Verify database credentials di cPanel
```

### Error: "500 Internal Server Error"
```bash
# Check error logs:
tail -f storage/logs/laravel.log
# Check server error logs di cPanel
```

---

## 📊 DEPLOYMENT CHECKLIST

### Pre-Deployment
- [ ] ✅ Backup database
- [ ] ✅ Backup existing files
- [ ] ✅ Verify database credentials
- [ ] ✅ Test server access (SSH/cPanel)

### Deployment
- [ ] ✅ Clean server directory
- [ ] ✅ Download/upload project files
- [ ] ✅ Set file permissions
- [ ] ✅ Configure .env file
- [ ] ✅ Install dependencies (vendor/)
- [ ] ✅ Run Laravel setup commands

### Post-Deployment
- [ ] ✅ Test artisan commands
- [ ] ✅ Test database connection
- [ ] ✅ Test website URLs
- [ ] ✅ Test API endpoints
- [ ] ✅ Test admin panel
- [ ] ✅ Check error logs

### Cleanup
- [ ] ✅ Remove deployment scripts
- [ ] ✅ Clear temporary files
- [ ] ✅ Monitor for 24 hours
- [ ] ✅ Document deployment notes

---

## 🎯 DEPLOYMENT STRATEGIES

### Strategi 1: Zero-Downtime (Advanced)
- Setup staging environment
- Test thoroughly
- Switch DNS/symlinks
- Rollback plan ready

### Strategi 2: Maintenance Mode (Recommended)
```bash
# Enable maintenance
php artisan down --message="Upgrading system"

# Deploy new version
# ... deployment steps ...

# Disable maintenance
php artisan up
```

### Strategi 3: Fresh Install (Current)
- Backup everything
- Clean install
- Restore data if needed
- Test and verify

---

**🚀 RECOMMENDED APPROACH:** Gunakan **METODE 1** atau **METODE 2** untuk deployment yang reliable dan cepat.

**⏱️ Estimated Time:** 15-30 menit (tergantung koneksi internet dan server performance)
