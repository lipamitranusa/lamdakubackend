# 🚨 FIX: chmod cannot access 'artisan' No such file or directory

## ⚠️ ERROR: File artisan tidak ditemukan saat set permissions

**Masalah:** File `artisan` tidak ada di direktori server (biasanya karena git clone membuat folder `lamdakubackend` bukan langsung ke folder API)

---

## 🔍 LANGKAH 1: Diagnosa Masalah

```bash
# 1. Cek direktori saat ini
pwd
# Harus di: /home/u329849080/domains/lamdaku.com/public_html

# 2. Cek file yang ada
ls -la
# Lihat apakah ada file artisan

# 3. Cek apakah ada folder lamdakubackend
ls -la lamdakubackend/ 2>/dev/null
# Jika ada, file ada di dalam folder ini

# 4. Cari file artisan di subdirektori
find . -name "artisan" -type f 2>/dev/null
# Akan menampilkan lokasi file artisan jika ada

# 5. Cek apakah di folder salah
ls -la */artisan 2>/dev/null
```

---

## 🔧 SOLUSI BERDASARKAN KONDISI

### KONDISI 1: Git clone membuat folder `lamdakubackend` (PALING UMUM)

```bash
# Jika git clone membuat folder lamdakubackend, pindahkan isinya:
ls -la lamdakubackend/
# Cek apakah ada artisan di dalam folder

# Pindahkan semua file ke direktori root
mv lamdakubackend/* .
mv lamdakubackend/.* . 2>/dev/null || true
rm -rf lamdakubackend

# Verifikasi artisan sekarang ada di root
ls -la artisan

# Set permissions
chmod +x artisan
```

### KONDISI 2: File artisan tidak ada sama sekali

```bash
# Download file artisan secara terpisah
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan

# Set permissions
chmod +x artisan

# Test
php artisan --version
```

### KONDISI 2: File artisan tidak ada sama sekali

```bash
# Download file artisan secara terpisah
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan

# Set permissions
chmod +x artisan

# Test
php artisan --version
```

### KONDISI 3: Project belum di-download/clone

```bash
# Clean dan download ulang project
rm -rf * .* 2>/dev/null || true

# Clone project ke direktori saat ini (gunakan titik di akhir)
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# ATAU jika clone membuat folder lamdakubackend:
git clone https://github.com/lipamitranusa/lamdakubackend.git
mv lamdakubackend/* .
mv lamdakubackend/.* . 2>/dev/null || true
rm -rf lamdakubackend

# Verifikasi artisan ada
ls -la artisan

# Set permissions
chmod +x artisan
```

### KONDISI 3: Project belum di-download/clone

```bash
# Clean dan download ulang project
rm -rf * .* 2>/dev/null || true

# Clone project ke direktori saat ini (gunakan titik di akhir)
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# ATAU jika clone membuat folder lamdakubackend:
git clone https://github.com/lipamitranusa/lamdakubackend.git
mv lamdakubackend/* .
mv lamdakubackend/.* . 2>/dev/null || true
rm -rf lamdakubackend

# Verifikasi artisan ada
ls -la artisan

# Set permissions
chmod +x artisan
```

### KONDISI 4: Artisan ada di subfolder

```bash
# Jika artisan ada di subfolder (misal: lamdakubackend-main/)
find . -name "artisan" -type f

# Pindahkan file ke root directory
mv */artisan . 2>/dev/null || true
mv */.* . 2>/dev/null || true
mv */* . 2>/dev/null || true

# Set permissions
chmod +x artisan
```

### KONDISI 5: Upload tidak lengkap

```bash
# Re-upload menggunakan wget
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
unzip main.zip
mv lamdakubackend-main/* .
mv lamdakubackend-main/.* . 2>/dev/null || true
rm -rf lamdakubackend-main main.zip

# Set permissions
chmod +x artisan
```

---

## 🚀 SOLUSI ONE-LINER (EMERGENCY FIX)

```bash
# COPY-PASTE COMMAND INI untuk fix artisan missing:
[ ! -f "artisan" ] && { echo "Downloading project..."; git clone https://github.com/lipamitranusa/lamdakubackend.git temp && mv temp/* . && mv temp/.* . 2>/dev/null || true && rm -rf temp; } || { echo "Artisan missing, downloading..."; wget -q https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan; } && chmod +x artisan && ls -la artisan && echo "✅ Project setup complete"
```

---

## 🔧 SOLUSI MANUAL CREATE ARTISAN

Jika download gagal, buat file artisan manual:

```bash
# Buat file artisan manual
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

# Set permissions
chmod +x artisan

# Test
ls -la artisan
php artisan --version
```

---

## 📋 COMPLETE PROJECT SETUP (jika project tidak lengkap)

```bash
# Script lengkap untuk setup project dari awal
cat > fix-missing-artisan.php << 'EOF'
<?php
echo "🔧 FIXING MISSING ARTISAN...\n";

// Check current directory
echo "📍 Current directory: " . getcwd() . "\n";

// List current files
echo "📂 Current files:\n";
$files = glob('*');
foreach($files as $file) {
    echo "  - $file\n";
}

// Check if artisan exists
if (!file_exists('artisan')) {
    echo "❌ artisan file not found\n";
    echo "📥 Downloading artisan...\n";
    
    // Try to download artisan
    $artisanContent = file_get_contents('https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan');
    if ($artisanContent !== false) {
        file_put_contents('artisan', $artisanContent);
        chmod('artisan', 0755);
        echo "✅ artisan downloaded and permissions set\n";
    } else {
        echo "❌ Failed to download artisan\n";
        echo "🔧 Creating basic artisan file...\n";
        
        $basicArtisan = '#!/usr/bin/env php
<?php

define("LARAVEL_START", microtime(true));

require __DIR__."/vendor/autoload.php";

$app = require_once __DIR__."/bootstrap/app.php";

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);';
        
        file_put_contents('artisan', $basicArtisan);
        chmod('artisan', 0755);
        echo "✅ Basic artisan created\n";
    }
} else {
    echo "✅ artisan file exists\n";
    chmod('artisan', 0755);
    echo "✅ artisan permissions set\n";
}

// Check if other Laravel files exist
$requiredFiles = ['composer.json', 'app', 'config', 'bootstrap'];
$missingFiles = [];

foreach($requiredFiles as $file) {
    if (!file_exists($file)) {
        $missingFiles[] = $file;
    }
}

if (!empty($missingFiles)) {
    echo "⚠️  Missing Laravel files: " . implode(', ', $missingFiles) . "\n";
    echo "📥 You may need to download complete project:\n";
    echo "   git clone https://github.com/lipamitranusa/lamdakubackend.git .\n";
    echo "   OR download ZIP and extract\n";
} else {
    echo "✅ All main Laravel files present\n";
}

// Test artisan
if (file_exists('artisan')) {
    echo "🧪 Testing artisan...\n";
    $output = shell_exec('php artisan --version 2>&1');
    if ($output && strpos($output, 'Laravel') !== false) {
        echo "✅ artisan working: " . trim($output) . "\n";
    } else {
        echo "⚠️  artisan needs dependencies (vendor/autoload.php)\n";
        echo "   Run: composer install --no-dev --optimize-autoloader\n";
    }
}

echo "🎉 Artisan fix completed!\n";
?>
EOF

# Run the fix script
php fix-missing-artisan.php
```

---

## 🎯 PREVENTION (untuk masa depan)

### Selalu verifikasi setelah download:

```bash
# Setelah git clone atau download, selalu cek:
ls -la artisan composer.json app/ config/

# Jika ada yang missing, re-download:
git pull origin master
# atau
wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
```

### Setup permissions script:

```bash
# Buat script untuk set permissions otomatis
cat > set-permissions.sh << 'EOF'
#!/bin/bash
echo "Setting Laravel permissions..."

# Artisan
if [ -f "artisan" ]; then
    chmod +x artisan
    echo "✅ artisan: 755"
else
    echo "❌ artisan not found"
fi

# Storage
if [ -d "storage" ]; then
    chmod -R 755 storage
    echo "✅ storage: 755"
fi

# Bootstrap cache
if [ -d "bootstrap/cache" ]; then
    chmod -R 755 bootstrap/cache
    echo "✅ bootstrap/cache: 755"
fi

# .env
if [ -f ".env" ]; then
    chmod 644 .env
    echo "✅ .env: 644"
fi

echo "✅ Permissions set!"
EOF

chmod +x set-permissions.sh
./set-permissions.sh
```

---

## 🚨 EMERGENCY: Project Setup Lengkap

Jika masalah persisten, gunakan script complete setup:

```bash
# Download dan jalankan script emergency setup
curl -s https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/fresh-deploy.php | php
```

---

## ✅ VERIFIKASI SETELAH FIX

```bash
# 1. Cek file artisan ada dan executable
ls -la artisan
# Output harus: -rwxr-xr-x ... artisan

# 2. Test artisan command
php artisan --version
# Output harus: Laravel Framework x.x.x

# 3. Cek struktur project
ls -la composer.json app/ config/ bootstrap/

# 4. Test basic functionality
php artisan list
```

---

## 🔴 TROUBLESHOOTING: php artisan --version tidak muncul

### Jika `ls -la artisan` sudah benar tapi `php artisan --version` tidak muncul:

```bash
# 1. Cek dependencies Laravel
ls -la vendor/autoload.php
# Jika tidak ada, dependencies belum terinstall

# 2. Cek error message
php artisan --version 2>&1
# Lihat error message lengkap

# 3. Cek struktur Laravel
ls -la bootstrap/app.php
# File ini harus ada untuk Laravel bisa jalan
```

### SOLUSI untuk php artisan --version tidak keluar:

```bash
# SOLUSI 1: Install dependencies
composer install --no-dev --optimize-autoloader

# SOLUSI 1b: Jika composer tidak ada, install dulu
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer
./composer install --no-dev --optimize-autoloader

# SOLUSI 2: Jika composer tidak tersedia, download vendor
wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip
unzip vendor.zip
rm vendor.zip

# SOLUSI 3: Download full project structure
rm -rf * .* 2>/dev/null || true
git clone https://github.com/lipamitranusa/lamdakubackend.git .
chmod +x artisan

# SOLUSI 4: Manual fix bootstrap
if [ ! -f "bootstrap/app.php" ]; then
    mkdir -p bootstrap
    wget -O bootstrap/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/bootstrap/app.php
fi

# SOLUSI 5: Test dengan error output
php -d display_errors=1 artisan --version
```

### ONE-LINER untuk fix dependencies:

```bash
# Fix lengkap jika artisan ada tapi tidak jalan (dengan install composer)
[ ! -f "vendor/autoload.php" ] && { echo "Installing dependencies..."; composer install --no-dev --optimize-autoloader 2>/dev/null || { echo "Installing Composer..."; curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && chmod +x composer && ./composer install --no-dev --optimize-autoloader; } || { echo "Downloading vendor..."; wget -q https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip && unzip -q vendor.zip && rm vendor.zip; }; } && php artisan --version && echo "✅ Laravel ready!"
```

### Cek manual error:

```bash
# Debug step by step
php -v                           # Cek PHP version
which php                       # Cek PHP location
cat artisan | head -5           # Cek shebang artisan
php -l artisan                  # Cek syntax artisan
php -d display_errors=1 -r "require 'vendor/autoload.php'; echo 'OK';" # Cek autoload
```

---

## 🔧 INSTALL ULANG COMPOSER

Jika Composer tidak tersedia atau rusak:

```bash
# ONE-LINER install Composer + dependencies
curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && chmod +x composer && ./composer install --no-dev --optimize-autoloader && echo "✅ Composer installed and Laravel ready!"

# Atau step by step:
# 1. Download Composer
curl -sS https://getcomposer.org/installer | php

# 2. Setup
mv composer.phar composer
chmod +x composer

# 3. Install dependencies
./composer install --no-dev --optimize-autoloader

# 4. Test
php artisan --version
```

### Alternatif install Composer:

```bash
# Jika curl tidak ada, gunakan wget
wget -O composer-setup.php https://getcomposer.org/installer
php composer-setup.php
rm composer-setup.php
mv composer.phar composer
chmod +x composer
```

**💡 TIP:** Simpan script `fix-missing-artisan.php` untuk emergency fix di masa depan!
