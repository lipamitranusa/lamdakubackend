# 🚀 QUICK REFERENCE: Laravel Deployment Fix Commands

## ⚡ MOST COMMON ISSUES & ONE-LINER FIXES

### 1. 🚨 chmod: cannot access 'artisan': No such file or directory

```bash
# Fix missing artisan (copy-paste ini):
[ ! -f "artisan" ] && { echo "Fixing artisan..."; git clone https://github.com/lipamitranusa/lamdakubackend.git temp && mv temp/* . && mv temp/.* . 2>/dev/null || true && rm -rf temp; } || echo "artisan exists" && chmod +x artisan && ls -la artisan && echo "✅ Artisan ready"
```

### 2. 🚨 Git clone membuat folder `lamdakubackend`

```bash
# Move files from subfolder (copy-paste ini):
[ -d "lamdakubackend" ] && { echo "Moving files..."; mv lamdakubackend/* . && mv lamdakubackend/.* . 2>/dev/null || true && rm -rf lamdakubackend; } && chmod +x artisan && echo "✅ Files moved to root"
```

### 3. 🚨 php artisan --version tidak muncul (dependencies missing)

```bash
# Install Composer + dependencies (copy-paste ini):
[ ! -f "vendor/autoload.php" ] && { echo "Installing..."; composer install --no-dev 2>/dev/null || { curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && chmod +x composer && ./composer install --no-dev; } || { wget -q https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip && unzip -q vendor.zip && rm vendor.zip; }; } && php artisan --version && echo "✅ Laravel ready!"
```

### 4. 🚨 Composer tidak ada/rusak

```bash
# Install Composer fresh (copy-paste ini):
curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && chmod +x composer && echo "✅ Composer installed - Version:" && ./composer --version
```

### 5. 🚨 Complete fresh setup

```bash
# Emergency complete setup (copy-paste ini):
rm -rf * .* 2>/dev/null || true && git clone https://github.com/lipamitranusa/lamdakubackend.git . && chmod +x artisan && composer install --no-dev --optimize-autoloader && echo "✅ Fresh setup complete"
```

---

## 🔍 DIAGNOSTIC COMMANDS

### Quick health check:
```bash
echo "=== HEALTH CHECK ===" && pwd && ls -la artisan vendor/autoload.php composer.json 2>/dev/null && php artisan --version 2>&1 && echo "✅ Status checked"
```

### Find missing files:
```bash
echo "=== MISSING FILES ===" && for file in artisan composer.json app config bootstrap vendor; do [ -e "$file" ] && echo "✅ $file" || echo "❌ $file MISSING"; done
```

### Test all Laravel components:
```bash
echo "=== LARAVEL TEST ===" && php -v && composer --version 2>/dev/null && php artisan --version && php artisan list | head -5 && echo "✅ All tests complete"
```

---

## 📋 EMERGENCY SCRIPTS

### All-in-one fix script:
```bash
# Create emergency fix script
cat > emergency-fix.sh << 'EOF'
#!/bin/bash
echo "🚨 EMERGENCY LARAVEL FIX"

# Fix folder structure
[ -d "lamdakubackend" ] && mv lamdakubackend/* . && mv lamdakubackend/.* . 2>/dev/null || true && rm -rf lamdakubackend

# Fix artisan
[ ! -f "artisan" ] && wget -q https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/artisan -O artisan

# Fix permissions
chmod +x artisan

# Fix composer
[ ! -f "composer" ] && [ ! -f "composer.phar" ] && {
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar composer
    chmod +x composer
}

# Fix dependencies
[ ! -f "vendor/autoload.php" ] && {
    ./composer install --no-dev --optimize-autoloader 2>/dev/null || 
    composer install --no-dev --optimize-autoloader 2>/dev/null ||
    { wget -q https://github.com/lipamitranusa/lamdakubackend/releases/download/vendor/vendor.zip && unzip -q vendor.zip && rm vendor.zip; }
}

# Test
php artisan --version && echo "✅ Emergency fix completed!"
EOF

chmod +x emergency-fix.sh
./emergency-fix.sh
```

---

## 🎯 STEP-BY-STEP TROUBLESHOOTING

### Step 1: Check current state
```bash
pwd && ls -la
```

### Step 2: Identify problem
```bash
[ -f "artisan" ] && echo "✅ artisan exists" || echo "❌ artisan missing"
[ -d "lamdakubackend" ] && echo "⚠️ subfolder issue" || echo "✅ no subfolder"
[ -f "vendor/autoload.php" ] && echo "✅ dependencies ok" || echo "❌ dependencies missing"
```

### Step 3: Apply appropriate fix
- If artisan missing → Use fix #1
- If subfolder exists → Use fix #2  
- If dependencies missing → Use fix #3
- If composer missing → Use fix #4
- If multiple issues → Use fix #5

### Step 4: Verify
```bash
php artisan --version && echo "✅ SUCCESS!"
```

---

## 🔗 REFERENCE GUIDES

- **Complete guide:** `FIX_ARTISAN_NOT_FOUND.md`
- **Composer install:** `INSTALL_COMPOSER_GUIDE.md`
- **Folder issues:** `QUICK_FIX_FOLDER_ISSUE.md`
- **Deployment guide:** `DEPLOYMENT_GUIDE.md`
- **Fresh deploy:** `REDEPLOY_GUIDE.md`

---

## 💡 PREVENTION TIPS

1. **Always clone with dot:**
   ```bash
   git clone https://github.com/lipamitranusa/lamdakubackend.git .
   ```

2. **Verify after clone:**
   ```bash
   ls -la artisan composer.json vendor/
   ```

3. **Set permissions immediately:**
   ```bash
   chmod +x artisan && chmod -R 755 storage bootstrap/cache
   ```

4. **Test before proceeding:**
   ```bash
   php artisan --version
   ```
