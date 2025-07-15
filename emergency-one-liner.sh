#!/bin/bash
# One-liner emergency setup for LAMDAKU CMS Backend
# Usage: curl -s https://your-domain.com/emergency-one-liner.sh | bash

echo "=== LAMDAKU CMS Emergency Setup ==="

# Check if we're in the right place
if [ ! -f "composer.json" ]; then
    echo "❌ No composer.json found. Attempting to get project files..."
    
    # Try git first
    if command -v git &> /dev/null; then
        echo "📥 Cloning from GitHub..."
        git clone https://github.com/lipamitranusa/lamdakubackend.git temp_clone
        mv temp_clone/* .
        mv temp_clone/.* . 2>/dev/null || true
        rm -rf temp_clone
    else
        echo "📥 Downloading project files..."
        wget -q https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip
        unzip -q main.zip
        mv lamdakubackend-main/* .
        mv lamdakubackend-main/.* . 2>/dev/null || true
        rm -rf lamdakubackend-main main.zip
    fi
fi

# Check composer version and try to upgrade
echo "🔧 Checking Composer..."
if command -v composer &> /dev/null; then
    composer self-update --2 2>/dev/null || echo "⚠️ Could not upgrade Composer"
    
    echo "📦 Installing dependencies..."
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs || {
        echo "❌ Composer install failed. Trying alternative..."
        php emergency-project-setup.php 2>/dev/null || echo "❌ Emergency setup also failed"
    }
else
    echo "❌ Composer not found"
fi

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# Create .env if not exists
if [ ! -f ".env" ]; then
    echo "⚙️ Creating .env..."
    cp .env.production .env 2>/dev/null || cp .env.example .env 2>/dev/null || {
        cat > .env << EOF
APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=base64:$(openssl rand -base64 32)
APP_DEBUG=false
APP_URL=https://lamdaku.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_PASSWORD_HERE
EOF
    }
fi

# Run setup if available
if [ -f "setup-lamdaku.php" ]; then
    echo "🚀 Running setup..."
    php setup-lamdaku.php
else
    echo "⚠️ setup-lamdaku.php not found"
fi

echo "✅ Emergency setup complete!"
echo "📋 Next steps:"
echo "1. Update DB_PASSWORD in .env"
echo "2. Run: php artisan migrate"
echo "3. Test your site"
