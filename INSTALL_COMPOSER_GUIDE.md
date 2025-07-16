# 🔧 CARA INSTALL ULANG COMPOSER

## 📦 Install Composer di Shared Hosting (Hostinger)

### METHOD 1: Install Composer Manual (PALING MUDAH)

```bash
# Download composer.phar
curl -sS https://getcomposer.org/installer | php

# Buat alias global
mv composer.phar composer

# Set permissions
chmod +x composer

# Test
./composer --version
```

### METHOD 2: Install ke PATH Global

```bash
# Download ke direktori user
cd ~/
curl -sS https://getcomposer.org/installer | php

# Pindah ke bin directory
mkdir -p ~/bin
mv composer.phar ~/bin/composer
chmod +x ~/bin/composer

# Add to PATH (tambah ke ~/.bashrc)
echo 'export PATH="$HOME/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc

# Test
composer --version
```

### METHOD 3: Install Local per Project

```bash
# Di direktori project
cd /home/u329849080/domains/lamdaku.com/public_html

# Download composer local
curl -sS https://getcomposer.org/installer | php

# Gunakan dengan php
php composer.phar --version
php composer.phar install --no-dev --optimize-autoloader
```

---

## 🚀 ONE-LINER INSTALL COMPOSER

```bash
# Install composer langsung dan install dependencies
curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && chmod +x composer && ./composer install --no-dev --optimize-autoloader && echo "✅ Composer installed and dependencies ready!"
```

---

## 🔧 TROUBLESHOOTING COMPOSER

### ERROR: curl command not found

```bash
# Gunakan wget sebagai alternatif
wget -O composer-setup.php https://getcomposer.org/installer
php composer-setup.php
rm composer-setup.php
mv composer.phar composer
chmod +x composer
```

### ERROR: php command not found

```bash
# Cari lokasi PHP
which php
# atau
whereis php
# atau
find /usr -name "php*" 2>/dev/null

# Gunakan path lengkap
/usr/bin/php composer.phar --version
```

### ERROR: Memory limit

```bash
# Install dengan memory limit tinggi
php -d memory_limit=2G composer.phar install --no-dev --optimize-autoloader
```

### ERROR: Permission denied

```bash
# Set permissions yang benar
chmod +x composer
chmod 755 vendor/ -R
```

---

## 📋 VERIFIKASI COMPOSER INSTALLATION

```bash
# Test composer version
composer --version
# atau jika local:
./composer --version
# atau dengan php:
php composer.phar --version

# Test composer command
composer list | head -10

# Test install dependencies
composer install --dry-run
```

---

## 🎯 COMPLETE SETUP DENGAN COMPOSER BARU

### Script lengkap install composer + dependencies:

```bash
# COPY-PASTE script ini untuk setup lengkap
cat > install-composer-and-deps.sh << 'EOF'
#!/bin/bash
echo "🔧 Installing Composer and Laravel Dependencies..."

# 1. Download Composer
echo "📥 Downloading Composer..."
curl -sS https://getcomposer.org/installer | php

# 2. Setup Composer
echo "⚙️ Setting up Composer..."
mv composer.phar composer
chmod +x composer

# 3. Test Composer
echo "🧪 Testing Composer..."
./composer --version

# 4. Install Laravel Dependencies
echo "📦 Installing Laravel dependencies..."
./composer install --no-dev --optimize-autoloader

# 5. Set permissions
echo "🔐 Setting permissions..."
chmod +x artisan
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# 6. Test Laravel
echo "🧪 Testing Laravel..."
php artisan --version

echo "✅ Setup completed!"
EOF

chmod +x install-composer-and-deps.sh
./install-composer-and-deps.sh
```

---

## 🚨 ALTERNATIF: TANPA COMPOSER

Jika install Composer tidak memungkinkan, gunakan vendor pre-compiled:

```bash
# Download vendor yang sudah di-compile
wget https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip
unzip vendor.zip
rm vendor.zip

# Test
php artisan --version
```

---

## 🔄 UPDATE COMPOSER

```bash
# Update composer ke versi terbaru
composer self-update

# Atau download ulang
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer
```

---

## 💡 TIPS COMPOSER DI SHARED HOSTING

### 1. Gunakan local composer jika global tidak tersedia:
```bash
php composer.phar install
```

### 2. Optimasi untuk production:
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

### 3. Clear cache jika ada masalah:
```bash
composer clear-cache
composer dump-autoload
```

### 4. Install dengan memory limit:
```bash
php -d memory_limit=2G composer.phar install --no-dev
```

---

## ✅ VERIFIKASI FINAL

```bash
# Cek semua komponen
echo "=== COMPOSER CHECK ==="
composer --version || ./composer --version || php composer.phar --version

echo -e "\n=== VENDOR CHECK ==="
ls -la vendor/autoload.php

echo -e "\n=== ARTISAN CHECK ==="
php artisan --version

echo -e "\n=== PROJECT STRUCTURE ==="
ls -la artisan composer.json vendor/ app/ config/ bootstrap/

echo "✅ All checks completed!"
```
