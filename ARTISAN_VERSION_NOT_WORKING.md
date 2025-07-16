# 🔧 FIX: Artisan Ada Tapi `php artisan --version` Tidak Muncul

## ⚠️ MASALAH: File artisan ada tapi command tidak jalan

**Gejala:**
- `ls -la artisan` menunjukkan file ada dan permissions benar (-rwxr-xr-x)
- `php artisan --version` tidak mengeluarkan output atau error

---

## 🔍 DIAGNOSIS MASALAH

### Langkah 1: Cek Dependencies

```bash
# Cek apakah vendor/autoload.php ada
ls -la vendor/autoload.php
# Jika tidak ada: Fatal error: require(): Failed opening required vendor/autoload.php

# Cek struktur Laravel lengkap
ls -la bootstrap/app.php config/app.php
# Jika tidak ada: Laravel tidak dapat bootstrap
```

### Langkah 2: Test Error Message

```bash
# Jalankan dengan error display
php -d display_errors=1 artisan --version

# Atau cek error message
php artisan --version 2>&1

# Test manual require
php -r "require 'vendor/autoload.php';"
```

---

## 🔧 SOLUSI BERDASARKAN ERROR

### ERROR 1: vendor/autoload.php tidak ada

```bash
# SOLUSI A: Install dengan composer
composer install --no-dev --optimize-autoloader

# SOLUSI B: Download vendor manual (jika composer tidak ada)
wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip
unzip vendor.zip
rm vendor.zip
```

### ERROR 2: bootstrap/app.php tidak ada

```bash
# Download file bootstrap yang hilang
mkdir -p bootstrap
wget -O bootstrap/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/bootstrap/app.php

# Test lagi
php artisan --version
```

### ERROR 3: config/app.php tidak ada

```bash
# Download config yang hilang
mkdir -p config
wget -O config/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/config/app.php

# Test lagi
php artisan --version
```

### ERROR 4: PHP path atau version issues

```bash
# Cek PHP version
php -v

# Test artisan dengan path lengkap
/usr/bin/php artisan --version

# Cek shebang artisan
head -1 artisan
# Harus: #!/usr/bin/env php
```

---

## 🚀 SOLUSI ONE-LINER

### Fix dependencies otomatis:

```bash
# Command lengkap untuk fix artisan yang tidak jalan
[ ! -f "vendor/autoload.php" ] && { echo "Installing dependencies..."; composer install --no-dev 2>/dev/null || { echo "Downloading vendor..."; wget -q https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip && unzip -q vendor.zip && rm vendor.zip; }; } && [ ! -f "bootstrap/app.php" ] && { echo "Downloading bootstrap..."; mkdir -p bootstrap && wget -q -O bootstrap/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/bootstrap/app.php; } && php artisan --version && echo "✅ Laravel ready!"
```

### Debug lengkap:

```bash
# Diagnostic lengkap jika masih bermasalah
echo "=== ARTISAN DEBUG ===" && ls -la artisan && echo -e "\n=== PHP VERSION ===" && php -v && echo -e "\n=== VENDOR CHECK ===" && ls -la vendor/autoload.php && echo -e "\n=== BOOTSTRAP CHECK ===" && ls -la bootstrap/app.php && echo -e "\n=== CONFIG CHECK ===" && ls -la config/app.php && echo -e "\n=== ARTISAN TEST ===" && php -d display_errors=1 artisan --version 2>&1
```

---

## 🔧 MANUAL STEP-BY-STEP FIX

### Jika one-liner tidak berhasil:

```bash
# 1. Backup dan diagnosa
echo "Current status:" && ls -la artisan vendor/ bootstrap/ config/ 2>/dev/null

# 2. Fix vendor
if [ ! -f "vendor/autoload.php" ]; then
    echo "Fixing vendor..."
    composer install --no-dev --optimize-autoloader || {
        echo "Composer failed, downloading vendor..."
        wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip
        unzip vendor.zip && rm vendor.zip
    }
fi

# 3. Fix bootstrap
if [ ! -f "bootstrap/app.php" ]; then
    echo "Fixing bootstrap..."
    mkdir -p bootstrap
    wget -O bootstrap/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/bootstrap/app.php
fi

# 4. Fix config
if [ ! -f "config/app.php" ]; then
    echo "Fixing config..."
    mkdir -p config
    wget -O config/app.php https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/config/app.php
fi

# 5. Test final
echo "Testing artisan..."
php artisan --version
```

---

## 🚨 NUCLEAR OPTION: Complete Re-download

Jika masih tidak berhasil, download ulang project lengkap:

```bash
# Backup .env jika ada
cp .env .env.backup 2>/dev/null || true

# Clean dan download ulang
rm -rf * .* 2>/dev/null || true
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Restore .env
mv .env.backup .env 2>/dev/null || true

# Set permissions
chmod +x artisan

# Test
php artisan --version
```

---

## ✅ VERIFIKASI SUCCESS

```bash
# 1. Artisan version harus muncul
php artisan --version
# Output: Laravel Framework 8.x.x

# 2. Artisan list harus jalan
php artisan list | head -10

# 3. Structure lengkap
ls -la artisan vendor/autoload.php bootstrap/app.php config/app.php

# 4. Ready untuk next steps
echo "✅ Laravel artisan ready for deployment!"
```

---

## 💡 PREVENTION

### Selalu download dependencies:

```bash
# Setelah git clone, selalu:
composer install --no-dev --optimize-autoloader

# Atau gunakan script otomatis:
php fix-missing-artisan.php
```

### Buat script test otomatis:

```bash
cat > test-artisan.sh << 'EOF'
#!/bin/bash
echo "Testing artisan setup..."
if [ -f "artisan" ] && [ -f "vendor/autoload.php" ] && [ -f "bootstrap/app.php" ]; then
    php artisan --version && echo "✅ Artisan working!"
else
    echo "❌ Missing required files"
    ls -la artisan vendor/autoload.php bootstrap/app.php 2>/dev/null
fi
EOF

chmod +x test-artisan.sh
./test-artisan.sh
```
