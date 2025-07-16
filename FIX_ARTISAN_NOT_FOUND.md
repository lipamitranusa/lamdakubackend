# 🚨 FIX: chmod cannot access 'artisan' No such file or directory

## ⚠️ ERROR: File artisan tidak ditemukan saat set permissions

**Masalah:** File `artisan` tidak ada di direktori server

---

## 🔍 LANGKAH 1: Diagnosa Masalah

```bash
# 1. Cek direktori saat ini
pwd
# Harus di: /home/u329849080/domains/lamdaku.com/public_html

# 2. Cek file yang ada
ls -la
# Lihat apakah ada file artisan

# 3. Cari file artisan di subdirektori
find . -name "artisan" -type f 2>/dev/null
# Akan menampilkan lokasi file artisan jika ada

# 4. Cek apakah di folder salah
ls -la */artisan 2>/dev/null
```

---

## 🔧 SOLUSI BERDASARKAN KONDISI

### KONDISI 1: File artisan tidak ada sama sekali

```bash
# Download file artisan secara terpisah
wget https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan

# Set permissions
chmod +x artisan

# Test
php artisan --version
```

### KONDISI 2: Project belum di-download/clone

```bash
# Clean dan download ulang project
rm -rf * .* 2>/dev/null || true

# Clone project
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Verifikasi artisan ada
ls -la artisan

# Set permissions
chmod +x artisan
```

### KONDISI 3: Artisan ada di subfolder

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

### KONDISI 4: Upload tidak lengkap

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
[ ! -f "artisan" ] && { echo "Artisan missing, downloading..."; wget -q https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan && echo "✅ Artisan downloaded"; } && chmod +x artisan && ls -la artisan && echo "✅ Artisan permissions set"
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

**💡 TIP:** Simpan script `fix-missing-artisan.php` untuk emergency fix di masa depan!
