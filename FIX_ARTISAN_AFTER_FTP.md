# 🔧 FIX ARTISAN ERROR - Setelah Upload FTP

## ❌ MASALAH: "Could not open input file: artisan"

**Situasi:** Sudah upload file via FTP tapi artisan tidak bisa dijalankan

### 🔍 DIAGNOSIS MASALAH

#### Step 1: Cek File Artisan Ada
```bash
# Via cPanel Terminal:
ls -la artisan

# Expected output:
# -rwxr-xr-x 1 user user 1733 Jan 1 12:00 artisan

# Jika tidak ada output = file tidak ada
# Jika ada tapi permission beda = masalah permissions
```

#### Step 2: Cek Lokasi File
```bash
# Pastikan Anda di direktori yang benar:
pwd
# Should be: /home/u329849080/domains/lamdaku.com/public_html

# Cek isi direktori:
ls -la
# Harus ada: artisan, composer.json, app/, bootstrap/, dll.
```

### 🛠️ SOLUSI BERDASARKAN MASALAH

#### MASALAH A: File artisan tidak ada

**Penyebab:** Upload FTP tidak lengkap atau file terlewat

**Solusi:**
```bash
# 1. Download file artisan manual:
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan

# 2. Atau via cPanel File Manager:
# - Download artisan dari: https://github.com/lipamitranusa/lamdakubackend/blob/main/artisan
# - Upload ke public_html/

# 3. Set permissions:
chmod +x artisan
chmod 755 artisan

# 4. Test:
php artisan --version
```

#### MASALAH B: File artisan ada tapi permission salah

**Penyebab:** File tidak executable

**Solusi:**
```bash
# 1. Set executable permission:
chmod +x artisan
chmod 755 artisan

# 2. Cek permissions:
ls -la artisan
# Harus: -rwxr-xr-x (executable)

# 3. Test:
php artisan --version
```

#### MASALAH C: File artisan rusak/corrupt

**Penyebab:** Upload error, encoding issues

**Solusi:**
```bash
# 1. Cek isi file:
head -5 artisan
# Harus start dengan:
# #!/usr/bin/env php
# <?php

# 2. Jika rusak, download ulang:
rm artisan
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
chmod +x artisan

# 3. Test:
php artisan --version
```

#### MASALAH D: Dependencies missing (vendor/)

**Penyebab:** Composer belum dijalankan

**Solusi:**
```bash
# 1. Install dependencies:
composer install --no-dev --optimize-autoloader

# 2. Jika composer tidak ada, download vendor manual:
# - Di local: composer install
# - Upload folder vendor/ via FTP

# 3. Test artisan:
php artisan --version
```

### 🚀 QUICK FIX ALL-IN-ONE

**Copy-paste command ini untuk fix semua masalah umum:**

```bash
# Masuk direktori yang benar
cd /home/u329849080/domains/lamdaku.com/public_html

# Download artisan yang benar (jika tidak ada/rusak)
wget -O artisan https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan

# Set permissions yang benar
chmod +x artisan
chmod 755 artisan
chmod -R 755 storage bootstrap/cache

# Test artisan
php artisan --version

echo "✅ Artisan should be working now!"
```

### 📋 MANUAL STEPS (Tanpa Terminal)

**Jika tidak ada akses Terminal, gunakan cPanel File Manager:**

1. **Download artisan file:**
   - Buka: https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
   - Save as: `artisan` (tanpa extension)

2. **Upload via cPanel:**
   - File Manager → public_html/
   - Upload file `artisan`
   - Overwrite jika sudah ada

3. **Set permissions:**
   - Klik kanan `artisan` → Permissions → **755**
   - ✅ Check "Execute" for Owner, Group, Others

4. **Test via cPanel Terminal:**
   ```bash
   php artisan --version
   ```

### 🧪 VERIFIKASI ARTISAN BEKERJA

```bash
# 1. Test version:
php artisan --version
# Expected: Laravel Framework 8.x.x

# 2. Test commands list:
php artisan list

# 3. Test specific command:
php artisan config:cache

# 4. Test migration (jika database ready):
php artisan migrate --force
```

### 🔧 TROUBLESHOOTING LANJUTAN

#### Error: "vendor/autoload.php not found"
```bash
# Install Composer dependencies:
composer install --no-dev --optimize-autoloader

# Jika tidak bisa, upload vendor/ manual dari development
```

#### Error: "bootstrap/app.php not found"
```bash
# File project tidak lengkap, re-upload semua file:
# Download: https://github.com/lipamitranusa/lamdakubackend/archive/main.zip
# Extract dan upload ulang semua file
```

#### Error: "Class not found"
```bash
# Regenerate autoloader:
composer dump-autoload
# Atau: php artisan clear-compiled
```

---

## ✅ CHECKLIST ARTISAN WORKING

- [ ] File `artisan` ada di root directory
- [ ] Permissions `artisan` = 755 (executable)
- [ ] File tidak corrupt (starts with `#!/usr/bin/env php`)
- [ ] Folder `vendor/` ada (dari composer install)
- [ ] Command `php artisan --version` berjalan
- [ ] Laravel framework version muncul

**🎉 Jika semua checklist ✅, artisan sudah siap digunakan!**
