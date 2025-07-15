# 🚨 FIX MISSING ARTISAN - SOLUSI GIT

## ⚠️ MASALAH: File `artisan` Tidak Ada di Server

**ROOT CAUSE:** File-file Laravel project tidak ada di server (artisan, composer.json, app/, dll.)

## 🎯 SOLUSI UTAMA: Gunakan GIT untuk Download Project

### LANGKAH 1: Cek dan Bersihkan Direktori

```bash
# 1. Cek lokasi saat ini
pwd
# Pastikan di: /home/u329849080/domains/lamdaku.com/public_html

# 2. Lihat apa yang ada di direktori
ls -la

# 3. Jika ada file lama, backup dulu (opsional)
mv * backup_old/ 2>/dev/null || true

# 4. Bersihkan direktori untuk git clone
rm -rf * .*
```

### LANGKAH 2: Clone Project dari GitHub

```bash
# 1. Clone project LAMDAKU dari GitHub
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# 2. Cek apakah file artisan sudah ada
ls -la artisan
# Harus muncul: -rwxr-xr-x 1 user user 1733 Jan 1 12:00 artisan

# 3. Cek file-file Laravel lainnya
ls -la composer.json app/ bootstrap/ config/
```

### LANGKAH 3: Verifikasi Project Structure

```bash
# 1. Pastikan semua file Laravel ada
ls -la
# Harus ada: artisan, composer.json, app/, bootstrap/, config/, dll.

# 2. Cek isi direktori penting
ls -la app/
ls -la database/migrations/
ls -la resources/views/

# 3. Test artisan command
php artisan --version
# Harus output: Laravel Framework x.x.x
```

### LANGKAH 4: Setup Environment

```bash
# 1. Copy .env file
cp .env.production .env

# 2. Edit .env file untuk update database password
nano .env
# Ganti: DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE
# Dengan: DB_PASSWORD=password_database_asli_anda

# 3. Set permissions
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### LANGKAH 5: Install Dependencies & Setup

```bash
# 1. Install dependencies dengan Composer
composer install --no-dev --optimize-autoloader

# 2. Generate application key
php artisan key:generate

# 3. Create storage link
php artisan storage:link

# 4. Run migrations
php artisan migrate --force

# 5. Cache config
php artisan config:cache
```

## 🚨 ALTERNATIVE: Jika Git Tidak Tersedia

### Solusi 1: Download Manual ZIP

## 🚨 ALTERNATIVE: Jika Git Tidak Tersedia

### Solusi 1: Download Manual ZIP

```bash
# 1. Download project sebagai ZIP file
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip

# 2. Extract ZIP file
unzip main.zip

# 3. Pindahkan file ke direktori utama
mv lamdakubackend-main/* .
mv lamdakubackend-main/.* . 2>/dev/null || true

# 4. Hapus folder dan file ZIP
rm -rf lamdakubackend-main main.zip

# 5. Verifikasi
ls -la artisan
```

### Solusi 2: Manual Upload via FTP

```bash
# 1. Di komputer local, download project
git clone https://github.com/lipamitranusa/lamdakubackend.git

# 2. Upload semua file via FTP/cPanel File Manager ke:
# /home/u329849080/domains/lamdaku.com/public_html/

# 3. Di server, set permissions
chmod -R 755 storage bootstrap/cache
chmod +x artisan
```

## ✅ VERIFIKASI SETUP BERHASIL

```bash
# 1. Test artisan
php artisan --version

# 2. Test API endpoint
curl https://lamdaku.com/api/status

# 3. Test di browser
# https://lamdaku.com/api
# https://lamdaku.com/admin (jika sudah setup)
```

## 🎯 ONE-LINER COMMAND (Copy-Paste)

```bash
# COPY-PASTE COMMAND INI DI SERVER:
cd /home/u329849080/domains/lamdaku.com/public_html && rm -rf * .* 2>/dev/null; git clone https://github.com/lipamitranusa/lamdakubackend.git . && cp .env.production .env && chmod -R 755 storage bootstrap/cache && chmod +x artisan && echo "✅ Project downloaded! Update DB_PASSWORD in .env then run: composer install"
```

## 🔧 TROUBLESHOOTING

### Error: "git: command not found"
```bash
# Gunakan wget download ZIP:
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip && unzip main.zip && mv lamdakubackend-main/* . && rm -rf lamdakubackend-main main.zip
```

### Error: "Permission denied"
```bash
# Set permissions:
chmod -R 755 storage bootstrap/cache
chmod +x artisan
chmod 644 .env
```

### Error: "Directory not empty"
```bash
# Backup dan bersihkan:
mkdir backup_$(date +%Y%m%d) 2>/dev/null && mv * backup_$(date +%Y%m%d)/ 2>/dev/null; rm -rf .* 2>/dev/null; git clone https://github.com/lipamitranusa/lamdakubackend.git .
```

---

## 📞 NEXT STEPS SETELAH GIT CLONE

1. ✅ **File artisan sudah ada** (dari git clone)
2. ⚙️ **Update .env** (ganti DB_PASSWORD)
3. 📦 **Run composer install**
4. 🚀 **Setup Laravel** (migrate, storage link, etc.)
5. 🌐 **Test website**

---

## SOLUSI: Upload/Clone Project Laravel

### CARA 1: Clone dari GitHub (Paling Mudah)

```bash
# 1. Hapus file-file lama (jika ada)
rm -rf * .??*

# 2. Clone project dari repository
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# 3. Verifikasi file Laravel ada
ls -la artisan composer.json app/
# Harus muncul semua file

# 4. Set permissions
chmod +x artisan
chmod -R 755 storage bootstrap/cache
```

### CARA 2: Jika Git Tidak Tersedia

```bash
# 1. Download project manual
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip

# 2. Extract
unzip main.zip

# 3. Pindahkan file ke root
mv lamdakubackend-main/* .
mv lamdakubackend-main/.* . 2>/dev/null || true

# 4. Cleanup
rm -rf lamdakubackend-main main.zip

# 5. Verifikasi
ls -la artisan composer.json app/
```

### CARA 3: Upload Manual via cPanel/FTP

1. **Download project dari GitHub** (di komputer local):
   - Buka: https://github.com/lipamitranusa/lamdakubackend
   - Klik "Code" → "Download ZIP"
   - Extract ZIP file

2. **Upload ke server via cPanel File Manager atau FTP**:
   - Upload semua file ke: `/home/u329849080/domains/lamdaku.com/public_html/`
   - Pastikan file `artisan`, `composer.json`, folder `app/` ada

3. **Set permissions via cPanel**:
   - File `artisan`: 755
   - Folder `storage/`: 755 (recursive)
   - Folder `bootstrap/cache/`: 755

### CARA 4: Emergency - Buat File Artisan Manual

Jika cara di atas tidak bisa, buat file artisan manual:

```bash
# 1. Buat file artisan
cat > artisan << 'EOF'
#!/usr/bin/env php
<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);
EOF

# 2. Set executable
chmod +x artisan

# 3. Test artisan
php artisan --version
```

## SKENARIO 2: Artisan Ada Tapi Error saat Dijalankan

### Error: "Could not open input file: artisan"

```bash
# 1. Cek apakah file artisan ada dan executable
ls -la artisan
file artisan

# 2. Jika ada tapi tidak executable
chmod +x artisan

# 3. Jika file corruption, download ulang
rm artisan
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
chmod +x artisan
```

### Error: "vendor/autoload.php not found"

```bash
# 1. Install dependencies dulu
composer install --no-dev --optimize-autoloader

# 2. Jika composer tidak ada, install composer dulu
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer
./composer install --no-dev --optimize-autoloader
```

### Error: "bootstrap/app.php not found"

```bash
# Project Laravel tidak lengkap, perlu re-upload/clone
# Gunakan CARA 1 atau CARA 2 di atas
```

## SKENARIO 3: Quick Complete Setup (All-in-One)

```bash
# ONE-LINER COMPLETE SETUP
cd /home/u329849080/domains/lamdaku.com/public_html && \
rm -rf * .??* && \
git clone https://github.com/lipamitranusa/lamdakubackend.git . && \
chmod +x artisan && \
chmod -R 755 storage bootstrap/cache && \
composer install --no-dev --optimize-autoloader && \
cp .env.example .env && \
php artisan key:generate && \
echo "✅ Setup complete! Update .env database settings"
```

## VERIFICATION STEPS

```bash
# 1. Cek file-file penting ada
ls -la artisan composer.json app/ public/ resources/

# 2. Test artisan command
php artisan --version
php artisan list

# 3. Cek structure Laravel
php artisan route:list
```

## ERROR TROUBLESHOOTING

### "PHP Fatal error: Uncaught Error: Class not found"
```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
composer dump-autoload
```

### "artisan: Permission denied"
```bash
chmod +x artisan
# Atau jalankan dengan php
php artisan --version
```

### "No such file or directory"
```bash
# File benar-benar tidak ada, perlu upload/clone project
# Gunakan CARA 1, 2, atau 3 di atas
```

---

## 🚀 QUICK TEST

Setelah setup berhasil:

```bash
# 1. Test artisan
php artisan --version

# 2. Test basic commands  
php artisan list
php artisan config:cache
php artisan route:cache

# 3. Test application
curl https://lamdaku.com/api
```

**PENTING:** Pastikan file `.env` sudah diupdate dengan database credentials yang benar!
