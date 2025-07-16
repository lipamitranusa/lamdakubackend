# 📁 SIMPLE FTP UPLOAD - LAMDAKU CMS

## 🎯 CARA TERMUDAH: Upload Manual via cPanel

### STEP 1: Download Project
1. Buka: https://github.com/lipamitranusa/lamdakubackend
2. Klik **"Code"** → **"Download ZIP"**
3. Extract di komputer: `lamdakubackend-main/`

### STEP 2: Upload ke Server
1. **Login cPanel** Hostinger
2. **Buka "File Manager"**
3. **Masuk ke:** `domains/lamdaku.com/public_html/`
4. **Hapus file lama** (Select All → Delete)
5. **Upload semua file** dari folder `lamdakubackend-main/`

### STEP 3: Set Permissions
**Via cPanel File Manager:**
- Klik kanan `artisan` → Permissions → **755**
- Klik kanan folder `storage` → Permissions → **755** ✅ Recursive
- Klik kanan folder `bootstrap` → Permissions → **755** ✅ Recursive

### STEP 4: Setup .env
1. **Rename** `.env.production` → `.env`
2. **Edit** file `.env`
3. **Ganti:** `DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE`
4. **Dengan:** `DB_PASSWORD=password_database_asli_anda`
5. **Save**

### STEP 5: Upload & Run Setup Script
1. **Upload** file `manual-setup.php` (sudah ada di project)
2. **Via Terminal/SSH** atau **cPanel Terminal:**
   ```bash
   php manual-setup.php
   ```

### STEP 6: Final Setup (Optional)
**Jika ada akses Terminal:**
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

## 🌐 TEST WEBSITE
- **Basic:** https://lamdaku.com
- **API:** https://lamdaku.com/api
- **Admin:** https://lamdaku.com/admin

---

## 🚨 TROUBLESHOOTING

### ❌ "Could not open input file: artisan" setelah upload FTP

**Masalah umum setelah upload via FTP:**

#### Solusi 1: Cek File Artisan Ada
```bash
# Via cPanel Terminal atau SSH:
ls -la artisan
# Harus output: -rwxr-xr-x 1 user user 1733 artisan

# Jika tidak ada, cek di folder:
find . -name "artisan" -type f
```

#### Solusi 2: Set Permissions Artisan
```bash
# Set executable permission:
chmod +x artisan
chmod 755 artisan

# Test lagi:
php artisan --version
```

#### Solusi 3: Cek Isi File Artisan
```bash
# Cek apakah file artisan rusak/kosong:
head -5 artisan
# Harus output:
# #!/usr/bin/env php
# <?php
# define('LARAVEL_START', microtime(true));
```

#### Solusi 4: Download Ulang File Artisan
```bash
# Jika file rusak, download artisan yang benar:
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
chmod +x artisan
php artisan --version
```

#### Solusi 5: Jalankan dengan PHP Langsung
```bash
# Jika masih error, gunakan php langsung:
php artisan --version
php artisan list
php artisan migrate --force
```

### "File tidak bisa diupload"
- Upload satu-satu atau per folder
- Gunakan mode Binary untuk file PHP
- Cek koneksi internet

### "Permission denied"
- Set permissions via cPanel File Manager
- artisan: 755, storage: 755, .env: 644

### "Website error 500"
- Cek file .env (password database benar?)
- Cek permissions storage/ dan bootstrap/cache/
- Lihat error log di cPanel → Error Logs

### "Composer not found"
- **SOLUSI TERBAIK:** Upload folder vendor/ manual dari komputer local
- **Lihat panduan lengkap:** `VENDOR_UPLOAD_MANUAL.md`
- Atau gunakan shared hosting yang support Composer

#### Quick Fix - Upload Vendor Manual:
```bash
# 1. Di komputer local:
composer install --no-dev --optimize-autoloader
zip -r vendor.zip vendor/

# 2. Upload vendor.zip ke server via cPanel
# 3. Extract di server:
unzip vendor.zip
chmod -R 755 vendor/
php artisan --version
```

### ❌ "Composer install tidak bisa dijalankan" setelah upload FTP

**Masalah umum:** Composer tidak tersedia atau error saat install dependencies

#### Solusi 1: Cek Composer Ada
```bash
# Cek apakah composer tersedia:
which composer
composer --version

# Jika tidak ada output, composer tidak terinstall
```

#### Solusi 2: Install Composer di Server
```bash
# Install Composer secara manual:
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Test:
composer --version
```

#### Solusi 3: Install Dependencies Manual (di Komputer Local)
**Jika composer tidak bisa diinstall di server, lakukan di komputer local:**

```bash
# 1. Di komputer local (Windows/Mac/Linux):
cd lamdakubackend-main/
composer install --no-dev --optimize-autoloader

# 2. Folder 'vendor' akan terbuat (size ~50-100MB)

# 3. Upload folder vendor/ ke server via FTP
# Upload ke: domains/lamdaku.com/public_html/vendor/

# 4. Set permissions vendor/:
chmod -R 755 vendor/
```

#### Solusi 4: Download Dependencies Pre-built
```bash
# Download vendor folder yang sudah di-build:
wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.tar.gz
tar -xzf vendor.tar.gz
chmod -R 755 vendor/
rm vendor.tar.gz
```

#### Solusi 5: NO-COMPOSER Mode (Emergency)
**Jika tidak bisa install composer sama sekali:**

```bash
# Buat script emergency tanpa composer:
cat > no-composer-run.php << 'EOF'
<?php
// Emergency script tanpa vendor/autoload.php

echo "=== NO-COMPOSER EMERGENCY MODE ===\n";

// Create minimal autoloader
if (!is_dir('vendor')) {
    mkdir('vendor', 0755, true);
}

$autoload = '<?php
// Minimal autoloader for emergency mode
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../app/",
        __DIR__ . "/../app/Http/Controllers/",
        __DIR__ . "/../app/Models/",
    ];
    
    foreach ($paths as $path) {
        $file = $path . str_replace("\\\\", "/", $class) . ".php";
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Basic Laravel functions
    if (!function_exists("env")) {
        function env($key, $default = null) {
            return $_ENV[$key] ?? getenv($key) ?? $default;
        }
    }
    
    if (!function_exists("config")) {
        function config($key, $default = null) {
            static $config = null;
            if (!$config) {
                $config = [
                    "app.name" => env("APP_NAME", "LAMDAKU CMS"),
                    "app.url" => env("APP_URL", "https://lamdaku.com"),
                    "database.default" => "mysql",
                    "database.connections.mysql.host" => env("DB_HOST", "localhost"),
                    "database.connections.mysql.database" => env("DB_DATABASE"),
                    "database.connections.mysql.username" => env("DB_USERNAME"),
                    "database.connections.mysql.password" => env("DB_PASSWORD"),
                ];
            }
            return $config[$key] ?? $default;
        }
    }
    
    return false;
});
?>';

file_put_contents('vendor/autoload.php', $autoload);
echo "✅ Emergency autoloader created\n";

// Test basic functionality
try {
    require 'vendor/autoload.php';
    echo "✅ Autoloader working\n";
    
    // Test .env loading
    if (file_exists('.env')) {
        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value, '\'" ');
                putenv(trim($key) . '=' . trim($value, '\'" '));
            }
        }
        echo "✅ .env loaded\n";
    }
    
    echo "🎉 Emergency mode setup complete!\n";
    echo "Your site should work in basic mode now.\n";
    echo "Test: https://lamdaku.com\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
EOF

php no-composer-run.php
```

#### Solusi 6: Gunakan Artisan Tanpa Dependencies
```bash
# Jalankan command Laravel tanpa full composer install:
php -r "
require 'vendor/autoload.php';
echo 'Testing basic Laravel...';
"

# Jika error, coba direct PHP commands:
php -f artisan -- --version
php -f artisan -- migrate --force
```

---

## ✅ CHECKLIST UPLOAD BERHASIL

- [ ] ✅ File `artisan` ada (755)
- [ ] ✅ File `composer.json` ada
- [ ] ✅ Folder `app/`, `config/`, `database/` ada
- [ ] ✅ File `.env` ada (copy dari .env.production)
- [ ] ✅ Permissions storage/ = 755
- [ ] ✅ Database password sudah diupdate di .env
- [ ] ✅ Website bisa diakses: https://lamdaku.com

**Total waktu: ~10-15 menit** ⏱️
