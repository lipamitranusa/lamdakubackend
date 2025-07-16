#!/bin/bash
# LAMDAKU CMS Fresh Deployment Script
# Auto backup, clean install, and setup

echo "🚀 LAMDAKU CMS FRESH DEPLOYMENT STARTING..."
echo "=========================================="

# Configuration
DOMAIN="lamdaku.com"
PROJECT_PATH="/home/u329849080/domains/$DOMAIN/public_html"
BACKUP_PATH="/home/u329849080/domains/$DOMAIN/backups"
REPO_URL="https://github.com/lipamitranusa/lamdakubackend.git"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Step 1: Pre-deployment checks
log_info "Step 1: Pre-deployment checks..."

# Check if we're in the right directory
if [ "$(pwd)" != "$PROJECT_PATH" ]; then
    log_info "Changing to project directory: $PROJECT_PATH"
    cd "$PROJECT_PATH" || {
        log_error "Cannot access project directory: $PROJECT_PATH"
        exit 1
    }
fi

log_success "Working directory: $(pwd)"

# Check PHP version
PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
log_info "PHP Version: $PHP_VERSION"

# Step 2: Backup existing installation
log_info "Step 2: Creating backup..."

# Create backup directory
mkdir -p "$BACKUP_PATH"
BACKUP_DIR="$BACKUP_PATH/backup_$TIMESTAMP"
mkdir -p "$BACKUP_DIR"

# Backup files
if [ "$(ls -A . 2>/dev/null)" ]; then
    log_info "Backing up existing files to: $BACKUP_DIR"
    cp -r * "$BACKUP_DIR/" 2>/dev/null || true
    cp -r .* "$BACKUP_DIR/" 2>/dev/null || true
    log_success "Files backed up successfully"
else
    log_info "No existing files to backup"
fi

# Backup database (if credentials are available)
if [ -f ".env" ]; then
    DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d'=' -f2)
    DB_USER=$(grep "^DB_USERNAME=" .env | cut -d'=' -f2)
    DB_PASS=$(grep "^DB_PASSWORD=" .env | cut -d'=' -f2)
    
    if [ -n "$DB_NAME" ] && [ -n "$DB_USER" ] && [ -n "$DB_PASS" ] && [ "$DB_PASS" != "YOUR_DATABASE_PASSWORD_HERE" ]; then
        log_info "Backing up database: $DB_NAME"
        mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/database_$TIMESTAMP.sql" 2>/dev/null
        if [ $? -eq 0 ]; then
            log_success "Database backed up successfully"
        else
            log_warning "Database backup failed (continuing anyway)"
        fi
    else
        log_warning "Database credentials not found or incomplete"
    fi
fi

# Step 3: Clean installation directory
log_info "Step 3: Cleaning installation directory..."

# Remove all files except this script
find . -mindepth 1 -maxdepth 1 ! -name "$(basename "$0")" -exec rm -rf {} \; 2>/dev/null || true
log_success "Directory cleaned"

# Step 4: Download latest project
log_info "Step 4: Downloading latest project..."

if command -v git &> /dev/null; then
    log_info "Using git clone..."
    git clone "$REPO_URL" . || {
        log_error "Git clone failed"
        exit 1
    }
else
    log_info "Git not available, using wget..."
    wget -q "$REPO_URL/archive/refs/heads/main.zip" -O project.zip || {
        log_error "Download failed"
        exit 1
    }
    
    if command -v unzip &> /dev/null; then
        unzip -q project.zip
        mv lamdakubackend-main/* .
        mv lamdakubackend-main/.* . 2>/dev/null || true
        rm -rf lamdakubackend-main project.zip
    else
        log_error "Unzip not available"
        exit 1
    fi
fi

log_success "Project downloaded successfully"

# Step 5: Set permissions
log_info "Step 5: Setting file permissions..."

chmod +x artisan
chmod -R 755 storage bootstrap/cache 2>/dev/null || true
chmod 644 .env.production 2>/dev/null || true

log_success "Permissions set"

# Step 6: Setup environment
log_info "Step 6: Setting up environment..."

# Copy .env file
if [ -f ".env.production" ]; then
    cp .env.production .env
    log_success ".env created from .env.production"
elif [ -f ".env.example" ]; then
    cp .env.example .env
    log_success ".env created from .env.example"
else
    log_error ".env template not found"
    exit 1
fi

# Restore database credentials from backup if available
if [ -f "$BACKUP_DIR/.env" ]; then
    BACKUP_DB_PASS=$(grep "^DB_PASSWORD=" "$BACKUP_DIR/.env" | cut -d'=' -f2)
    if [ -n "$BACKUP_DB_PASS" ] && [ "$BACKUP_DB_PASS" != "YOUR_DATABASE_PASSWORD_HERE" ]; then
        log_info "Restoring database password from backup"
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$BACKUP_DB_PASS/" .env
        log_success "Database password restored"
    fi
fi

chmod 644 .env

# Step 7: Install dependencies
log_info "Step 7: Installing dependencies..."

if command -v composer &> /dev/null; then
    log_info "Installing via Composer..."
    composer install --no-dev --optimize-autoloader --no-interaction || {
        log_warning "Composer install failed, trying with ignore platform reqs..."
        composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction || {
            log_error "Composer install failed completely"
        }
    }
else
    log_warning "Composer not available"
    log_info "You'll need to upload vendor/ folder manually"
fi

# Step 8: Laravel setup
log_info "Step 8: Laravel application setup..."

# Test if artisan works
if php artisan --version &>/dev/null; then
    log_success "Artisan is working"
    
    # Generate key if needed
    if ! grep -q "APP_KEY=base64:" .env; then
        log_info "Generating application key..."
        php artisan key:generate --force
        log_success "Application key generated"
    fi
    
    # Clear caches
    log_info "Clearing caches..."
    php artisan cache:clear &>/dev/null || true
    php artisan config:clear &>/dev/null || true
    php artisan route:clear &>/dev/null || true
    php artisan view:clear &>/dev/null || true
    
    # Create storage link
    if [ ! -L "public/storage" ]; then
        log_info "Creating storage link..."
        php artisan storage:link &>/dev/null || true
        log_success "Storage link created"
    fi
    
    # Check database connection
    DB_PASS=$(grep "^DB_PASSWORD=" .env | cut -d'=' -f2)
    if [ "$DB_PASS" != "YOUR_DATABASE_PASSWORD_HERE" ]; then
        log_info "Testing database connection..."
        if php artisan migrate:status &>/dev/null; then
            log_success "Database connection successful"
            
            # Ask about migrations
            echo -n "Run fresh migrations? This will DESTROY existing data! (y/N): "
            read -r response
            if [[ "$response" =~ ^[Yy]$ ]]; then
                log_warning "Running fresh migrations..."
                php artisan migrate:fresh --seed --force
                log_success "Fresh migrations completed"
            else
                log_info "Running safe migrations..."
                php artisan migrate --force
                log_success "Migrations completed"
            fi
        else
            log_warning "Database connection failed - check credentials in .env"
        fi
    else
        log_warning "Database password not set - please update .env file"
    fi
    
    # Cache for production
    log_info "Caching for production..."
    php artisan config:cache &>/dev/null || true
    php artisan route:cache &>/dev/null || true
    
else
    log_warning "Artisan not working - vendor dependencies may be missing"
fi

# Step 9: Final verification
log_info "Step 9: Final verification..."

# Check file structure
REQUIRED_FILES=("artisan" "composer.json" ".env" "app" "config" "database" "public")
MISSING_FILES=()

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -e "$file" ]; then
        MISSING_FILES+=("$file")
    fi
done

if [ ${#MISSING_FILES[@]} -eq 0 ]; then
    log_success "All required files present"
else
    log_error "Missing files: ${MISSING_FILES[*]}"
fi

# Test web response
log_info "Testing web response..."
if curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN" | grep -q "200\|301\|302"; then
    log_success "Website responding"
else
    log_warning "Website not responding (may need time to propagate)"
fi

# Step 10: Cleanup and summary
log_info "Step 10: Cleanup and summary..."

# Remove deployment script from web directory
if [ -f "$(basename "$0")" ]; then
    rm -f "$(basename "$0")"
    log_success "Deployment script cleaned up"
fi

echo ""
echo "=========================================="
echo "🎉 DEPLOYMENT COMPLETED!"
echo "=========================================="
echo ""
echo "📊 DEPLOYMENT SUMMARY:"
echo "  • Backup created: $BACKUP_DIR"
echo "  • Project downloaded from: $REPO_URL"
echo "  • Environment: Production"
echo "  • Domain: https://$DOMAIN"
echo ""
echo "📋 NEXT STEPS:"
echo "  1. 🔧 Update database password in .env (if needed)"
echo "  2. 🌐 Test website: https://$DOMAIN"
echo "  3. 🔍 Test API: https://$DOMAIN/api"
echo "  4. 👤 Test admin: https://$DOMAIN/admin"
echo "  5. 📝 Check logs: tail -f storage/logs/laravel.log"
echo ""
echo "🔧 TROUBLESHOOTING:"
echo "  • Check .env file for correct database credentials"
echo "  • Verify file permissions (storage/, bootstrap/cache/)"
echo "  • Check server error logs in cPanel"
echo "  • Run: php artisan migrate if database issues"
echo ""
echo "📞 Support files:"
echo "  • REDEPLOY_GUIDE.md - Detailed deployment guide"
echo "  • TROUBLESHOOTING.md - Common issues and solutions"
echo ""

log_success "Fresh deployment completed successfully! 🚀"
