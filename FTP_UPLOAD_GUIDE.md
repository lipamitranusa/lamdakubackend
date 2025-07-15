# 📁 UPLOAD MANUAL VIA FTP - LAMDAKU CMS

## 🎯 SOLUSI: Upload Project Manual (Tanpa Git/Command Line)

**Untuk situasi dimana git clone tidak bisa digunakan atau command line terbatas**

### 📥 LANGKAH 1: Download Project di Komputer Local

1. **Buka browser** dan kunjungi:
   ```
   https://github.com/lipamitranusa/lamdakubackend
   ```

2. **Klik tombol hijau "Code"** → **"Download ZIP"**

3. **Extract file ZIP** ke folder di komputer Anda
   - Contoh: `C:\Downloads\lamdakubackend-main\`

4. **Verifikasi file** yang diperlukan ada:
   - ✅ `artisan`
   - ✅ `composer.json`
   - ✅ Folder `app/`
   - ✅ Folder `bootstrap/`
   - ✅ Folder `config/`
   - ✅ Folder `database/`
   - ✅ Folder `public/`
   - ✅ Folder `resources/`
   - ✅ File `.env.production`

### 📤 LANGKAH 2: Upload ke Server via FTP

#### **Opsi A: Menggunakan cPanel File Manager** (Paling Mudah)

1. **Login ke cPanel** Hostinger Anda

2. **Buka "File Manager"**

3. **Navigasi ke folder website:**
   ```
   domains/lamdaku.com/public_html/
   ```

4. **Hapus file lama** (jika ada):
   - Select All → Delete
   - ATAU backup ke folder `backup_old/`

5. **Upload semua file project:**
   - Klik "Upload"
   - Pilih semua file dari folder `lamdakubackend-main/`
   - Upload folder demi folder jika diperlukan

6. **Extract jika upload dalam bentuk ZIP:**
   - Upload file `lamdakubackend-main.zip`
   - Right-click → Extract
   - Pindahkan semua file ke root directory

#### **Opsi B: Menggunakan FTP Client** (FileZilla, WinSCP, dll.)

1. **Download FTP Client** (contoh: FileZilla)

2. **Setup koneksi FTP:**
   ```
   Host: ftp.lamdaku.com
   Username: [FTP username dari Hostinger]
   Password: [FTP password dari Hostinger]
   Port: 21
   ```

3. **Connect dan navigasi ke:**
   ```
   /domains/lamdaku.com/public_html/
   ```

4. **Upload semua file** dari folder lokal ke server

### ⚙️ LANGKAH 3: Setup File & Permissions via cPanel

1. **Copy file .env:**
   - Rename `.env.production` menjadi `.env`
   - ATAU copy isi `.env.production` ke file baru `.env`

2. **Set permissions** via File Manager:
   - File `artisan`: 755
   - Folder `storage/`: 755 (recursive)
   - Folder `bootstrap/cache/`: 755 (recursive)
   - File `.env`: 644

3. **Edit file .env:**
   - Klik kanan `.env` → Edit
   - Ganti `YOUR_DATABASE_PASSWORD_HERE` dengan password database asli
   - Save

### 🔧 LANGKAH 4: Setup via Terminal/SSH (Jika Tersedia)

Jika Hostinger menyediakan terminal access:

```bash
# 1. Masuk ke direktori website
cd /home/u329849080/domains/lamdaku.com/public_html

# 2. Set permissions
chmod +x artisan
chmod -R 755 storage bootstrap/cache
chmod 644 .env

# 3. Test artisan
php artisan --version

# 4. Install dependencies (jika composer tersedia)
composer install --no-dev --optimize-autoloader

# 5. Generate key
php artisan key:generate

# 6. Run migrations
php artisan migrate --force

# 7. Create storage link
php artisan storage:link
```

## 📋 CHECKLIST UPLOAD MANUAL

### ✅ Files yang HARUS ada setelah upload:

```
public_html/
├── artisan (file, 755)
├── composer.json
├── .env (copy dari .env.production)
├── .htaccess
├── app/ (folder)
│   ├── Http/
│   ├── Models/
│   └── ...
├── bootstrap/ (folder, 755)
│   └── cache/ (folder, 755)
├── config/ (folder)
├── database/ (folder)
│   ├── migrations/
│   └── seeders/
├── public/ (folder)
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── resources/ (folder)
│   └── views/
├── routes/ (folder)
├── storage/ (folder, 755)
│   ├── app/
│   ├── framework/
│   └── logs/
└── vendor/ (folder, akan dibuat oleh composer)
```

### ⚠️ Permissions yang HARUS diset:

```
artisan: 755
storage/: 755 (recursive)
bootstrap/cache/: 755 (recursive)
.env: 644
public/.htaccess: 644
.htaccess: 644
```

## 🚨 TROUBLESHOOTING UPLOAD FTP

### Problem 1: "File upload failed"
**Solusi:**
- Upload file satu per satu atau folder per folder
- Cek koneksi internet
- Gunakan mode Binary untuk file PHP

### Problem 2: "Permission denied"
**Solusi:**
- Set folder permissions via cPanel File Manager
- Klik kanan folder → Change Permissions → 755

### Problem 3: "File not found after upload"
**Solusi:**
- Refresh File Manager
- Cek apakah upload ke direktori yang benar
- Pastikan tidak ada karakter khusus di nama file

### Problem 4: "Website tidak bisa diakses"
**Solusi:**
```bash
# Cek file .htaccess di root
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]

# Cek file public/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 🔧 SETUP ALTERNATIF (Tanpa Composer)

Jika composer tidak tersedia di server:

### 1. Setup Minimal di Local + Upload

```bash
# Di komputer local:
cd lamdakubackend-main/
composer install --no-dev --optimize-autoloader

# Upload folder vendor/ juga ke server
# Folder vendor/ biasanya besar (50-100MB)
```

### 2. Create Setup Script

Buat file `manual-setup.php` dan upload ke server:

```php
<?php
echo "=== MANUAL SETUP LAMDAKU ===\n";

// Set permissions
if (is_dir('storage')) {
    chmod('storage', 0755);
    system('find storage -type d -exec chmod 755 {} \;');
    system('find storage -type f -exec chmod 644 {} \;');
    echo "✅ Storage permissions set\n";
}

if (is_dir('bootstrap/cache')) {
    chmod('bootstrap/cache', 0755);
    echo "✅ Bootstrap cache permissions set\n";
}

if (file_exists('artisan')) {
    chmod('artisan', 0755);
    echo "✅ Artisan permissions set\n";
}

// Create .env if not exists
if (!file_exists('.env') && file_exists('.env.production')) {
    copy('.env.production', '.env');
    echo "✅ .env created from .env.production\n";
}

echo "🎉 Manual setup complete!\n";
echo "Don't forget to:\n";
echo "1. Update DB_PASSWORD in .env\n";
echo "2. Test: https://lamdaku.com\n";
?>
```

Kemudian jalankan:
```bash
php manual-setup.php
```

## 🌐 TEST WEBSITE

Setelah upload selesai:

1. **Test basic URL:**
   ```
   https://lamdaku.com
   ```

2. **Test API:**
   ```
   https://lamdaku.com/api
   https://lamdaku.com/api/status
   ```

3. **Test admin (jika sudah setup database):**
   ```
   https://lamdaku.com/admin
   ```

---

## 📞 LANGKAH SELANJUTNYA

Setelah upload berhasil:

1. ✅ **Files uploaded via FTP**
2. ⚙️ **Permissions set via cPanel**
3. 📝 **Edit .env file**
4. 🗃️ **Setup database** (manual atau via artisan)
5. 🌐 **Test website**

**File utama untuk troubleshooting:**
- `DEPLOYMENT_GUIDE.md` - Panduan lengkap deployment
- `setup-lamdaku.php` - Auto setup script (jika berhasil upload)
- `manual-setup.php` - Manual setup alternative
