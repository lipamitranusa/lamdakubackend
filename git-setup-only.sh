#!/bin/bash
# LAMDAKU CMS - Git Setup Only (Fokus Git)
# One-liner untuk setup menggunakan git clone

echo "🚀 LAMDAKU CMS - Git Setup Starting..."

# Cek direktori saat ini
echo "📍 Current directory: $(pwd)"

# Backup file existing (jika ada)
if [ "$(ls -A)" ]; then
    echo "📦 Backing up existing files..."
    mkdir -p backup_$(date +%Y%m%d_%H%M%S)
    mv * backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true
    mv .* backup_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true
fi

# Clone project dari GitHub
echo "📥 Cloning LAMDAKU project from GitHub..."
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Verifikasi file artisan
if [ -f "artisan" ]; then
    echo "✅ artisan file found!"
    chmod +x artisan
else
    echo "❌ artisan file not found after git clone"
    exit 1
fi

# Verifikasi composer.json
if [ -f "composer.json" ]; then
    echo "✅ composer.json found!"
else
    echo "❌ composer.json not found"
    exit 1
fi

# Setup .env
echo "⚙️ Setting up .env file..."
if [ -f ".env.production" ]; then
    cp .env.production .env
    echo "✅ .env copied from .env.production"
elif [ -f ".env.example" ]; then
    cp .env.example .env
    echo "✅ .env copied from .env.example"
else
    echo "⚠️ No .env template found, creating basic .env"
    cat > .env << EOF
APP_NAME="LAMDAKU CMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://lamdaku.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u329849080_lamdaku_prod
DB_USERNAME=u329849080_lamdaku_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

LOG_CHANNEL=stack
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
fi

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || true
chmod 644 .env

# Create .htaccess files if not exist
if [ ! -f ".htaccess" ]; then
    echo "🔧 Creating .htaccess..."
    cat > .htaccess << EOF
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
EOF
fi

if [ ! -f "public/.htaccess" ]; then
    echo "🔧 Creating public/.htaccess..."
    cat > public/.htaccess << EOF
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
fi

# Test artisan
echo "🧪 Testing artisan..."
if php artisan --version; then
    echo "✅ Artisan is working!"
else
    echo "⚠️ Artisan test failed, but files are in place"
fi

echo ""
echo "🎉 Git setup complete!"
echo ""
echo "📋 NEXT STEPS:"
echo "1. Update DB_PASSWORD in .env file:"
echo "   nano .env"
echo ""
echo "2. Install dependencies:"
echo "   composer install --no-dev --optimize-autoloader"
echo ""
echo "3. Generate app key:"
echo "   php artisan key:generate"
echo ""
echo "4. Run migrations:"
echo "   php artisan migrate --force"
echo ""
echo "5. Create storage link:"
echo "   php artisan storage:link"
echo ""
echo "6. Test your site:"
echo "   https://lamdaku.com"
echo ""
echo "✅ All Laravel files are now in place!"
