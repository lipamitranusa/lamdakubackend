#!/bin/bash

echo "=== LAMDAKU CMS QUICK DEPLOY SCRIPT ==="
echo "Date: $(date)"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from Laravel project root."
    exit 1
fi

echo "✅ Found Laravel project in: $(pwd)"
echo ""

# Step 1: Run main setup
echo "🔧 Step 1: Running main setup..."
php setup-lamdaku.php

# Step 2: Install dependencies
echo ""
echo "🔧 Step 2: Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Composer install failed"
    exit 1
fi

# Step 3: Generate APP_KEY
echo ""
echo "🔧 Step 3: Generating APP_KEY..."
php artisan key:generate --force

# Step 4: Check if database is configured
echo ""
echo "🔧 Step 4: Checking database configuration..."
if grep -q "YOUR_DATABASE_PASSWORD_HERE" .env; then
    echo "⚠️  Database password not configured!"
    echo "Please update DB_PASSWORD in .env file, then run:"
    echo "  php setup-database.php"
    echo "  php artisan storage:link"
    echo "  php artisan config:cache"
else
    echo "✅ Database credentials found, setting up database..."
    php setup-database.php
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "🔧 Step 5: Creating storage link..."
        php artisan storage:link
        
        echo ""
        echo "🔧 Step 6: Caching configurations..."
        php artisan config:cache
        
        echo ""
        echo "✅ DEPLOYMENT COMPLETED SUCCESSFULLY!"
        echo ""
        echo "🔗 Test your website:"
        echo "   - Status: https://lamdaku.com/setup-status.php"
        echo "   - Main: https://lamdaku.com"
        echo "   - Admin: https://lamdaku.com/admin/login"
    else
        echo "❌ Database setup failed"
        echo "Please check your database credentials and try again"
    fi
fi

echo ""
echo "📋 Don't forget to:"
echo "   1. Set document root to: public_html/api/public"
echo "   2. Update admin password after first login"
echo "   3. Remove setup files after testing"
