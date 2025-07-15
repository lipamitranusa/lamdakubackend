#!/bin/bash
# Quick Fix untuk Missing Artisan & Laravel Files
# Usage: curl -s https://your-domain.com/fix-missing-artisan.sh | bash

echo "=== QUICK FIX: Missing Artisan & Laravel Files ==="

# Check current directory
echo "📍 Current directory: $(pwd)"
echo "📂 Current contents:"
ls -la

# Check if Laravel files exist
if [ ! -f "artisan" ] || [ ! -f "composer.json" ] || [ ! -d "app" ]; then
    echo ""
    echo "❌ Laravel project files missing!"
    echo "📥 Downloading project from GitHub..."
    
    # Backup any existing files
    if [ "$(ls -A)" ]; then
        echo "📦 Backing up existing files..."
        mkdir -p backup-$(date +%Y%m%d-%H%M%S)
        mv * backup-$(date +%Y%m%d-%H%M%S)/ 2>/dev/null || true
        mv .* backup-$(date +%Y%m%d-%H%M%S)/ 2>/dev/null || true
    fi
    
    # Try git clone first
    if command -v git &> /dev/null; then
        echo "🔄 Cloning via Git..."
        git clone https://github.com/lipamitranusa/lamdakubackend.git .
    else
        echo "🔄 Downloading via wget..."
        # Download and extract
        wget -q https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip -O project.zip
        
        if command -v unzip &> /dev/null; then
            unzip -q project.zip
            mv lamdakubackend-main/* .
            mv lamdakubackend-main/.* . 2>/dev/null || true
            rm -rf lamdakubackend-main project.zip
        else
            echo "❌ Neither git nor unzip available!"
            echo "📋 Manual steps required:"
            echo "1. Download: https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip"
            echo "2. Extract and upload all files to current directory"
            exit 1
        fi
    fi
    
    # Verify download
    if [ -f "artisan" ] && [ -f "composer.json" ]; then
        echo "✅ Project files downloaded successfully!"
    else
        echo "❌ Download failed!"
        exit 1
    fi
else
    echo "✅ Laravel project files found!"
fi

# Set permissions
echo "🔐 Setting permissions..."
chmod +x artisan 2>/dev/null || true
chmod -R 755 storage 2>/dev/null || true
chmod -R 755 bootstrap/cache 2>/dev/null || true

# Test artisan
echo "🧪 Testing artisan..."
if php artisan --version &>/dev/null; then
    echo "✅ Artisan working!"
    php artisan --version
else
    echo "⚠️ Artisan needs dependencies..."
    
    # Try to install composer dependencies
    if command -v composer &> /dev/null; then
        echo "📦 Installing dependencies..."
        composer install --no-dev --optimize-autoloader --no-interaction
    else
        echo "❌ Composer not available!"
        echo "📋 Manual steps:"
        echo "1. Install composer: curl -sS https://getcomposer.org/installer | php"
        echo "2. Run: php composer.phar install --no-dev --optimize-autoloader"
    fi
fi

# Create/check .env
if [ ! -f ".env" ]; then
    echo "⚙️ Creating .env file..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
    elif [ -f ".env.production" ]; then
        cp .env.production .env
    else
        cat > .env << 'EOF'
APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
    fi
    echo "✅ .env created!"
else
    echo "✅ .env already exists!"
fi

# Create .htaccess if missing
if [ ! -f ".htaccess" ]; then
    echo "🔗 Creating .htaccess..."
    cat > .htaccess << 'EOF'
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
EOF
fi

if [ ! -f "public/.htaccess" ]; then
    echo "🔗 Creating public/.htaccess..."
    mkdir -p public
    cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
fi

echo ""
echo "🎉 QUICK FIX COMPLETE!"
echo ""
echo "📋 NEXT STEPS:"
echo "1. ✏️  Update database password in .env"
echo "2. 🔑 Run: php artisan key:generate"
echo "3. 🗃️  Run: php artisan migrate"
echo "4. 🧪 Test: php artisan --version"
echo ""
echo "🌐 Test URLs:"
echo "- https://lamdaku.com/api"
echo "- https://lamdaku.com/api/status"
echo ""
echo "📁 Current structure:"
ls -la artisan composer.json app/ public/ 2>/dev/null || echo "Some files may still be missing"
