#!/bin/bash
# Emergency Artisan Fix - One-liner
# Usage: curl -s https://your-domain.com/emergency-artisan-fix.sh | bash

echo "🔧 EMERGENCY ARTISAN FIX"

# Go to correct directory
cd /home/u329849080/domains/lamdaku.com/public_html 2>/dev/null || cd /var/www/html 2>/dev/null || cd .

echo "📍 Current directory: $(pwd)"

# Check if artisan exists
if [ ! -f "artisan" ]; then
    echo "❌ artisan file not found, downloading..."
    
    # Try to download artisan
    if command -v wget &> /dev/null; then
        wget -q -O artisan https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
    elif command -v curl &> /dev/null; then
        curl -s -o artisan https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan
    else
        echo "❌ Neither wget nor curl available"
        echo "📋 Manual steps:"
        echo "1. Download: https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/main/artisan"
        echo "2. Save as 'artisan' (no extension)"
        echo "3. Upload to public_html/"
        echo "4. Set permissions 755"
        exit 1
    fi
    
    if [ -f "artisan" ]; then
        echo "✅ artisan downloaded successfully"
    else
        echo "❌ Failed to download artisan"
        exit 1
    fi
else
    echo "✅ artisan file exists"
fi

# Set correct permissions
chmod +x artisan 2>/dev/null
chmod 755 artisan 2>/dev/null
echo "✅ artisan permissions set (755)"

# Check if file is executable and not empty
if [ -s "artisan" ] && [ -x "artisan" ]; then
    echo "✅ artisan file is valid and executable"
    
    # Test artisan
    echo "🧪 Testing artisan..."
    if php artisan --version 2>/dev/null; then
        echo "✅ artisan is working!"
    else
        echo "⚠️ artisan test failed, but file is in place"
        echo "💡 Try: php artisan --version"
    fi
else
    echo "❌ artisan file is not executable or empty"
fi

# Set other necessary permissions
chmod -R 755 storage 2>/dev/null
chmod -R 755 bootstrap/cache 2>/dev/null
echo "✅ storage and bootstrap/cache permissions set"

echo ""
echo "🎉 EMERGENCY ARTISAN FIX COMPLETE!"
echo ""
echo "📋 Test commands:"
echo "  php artisan --version"
echo "  php artisan list"
echo "  php artisan migrate --force"
echo ""
echo "🌐 Test website: https://lamdaku.com"
