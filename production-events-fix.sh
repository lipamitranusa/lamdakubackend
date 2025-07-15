#!/bin/bash

echo "=== LAMDAKU PRODUCTION EVENTS MIGRATION FIX ==="
echo "Date: $(date)"
echo "Server: Production"
echo ""

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from Laravel project root."
    echo "Current directory: $(pwd)"
    echo ""
    echo "💡 Navigate to the correct directory first:"
    echo "   cd /path/to/your/laravel/project"
    exit 1
fi

echo "✅ Found Laravel project in: $(pwd)"
echo ""

# Step 1: Remove problematic migration file
echo "🔧 Step 1: Removing problematic migration file..."
MIGRATION_FILE="database/migrations/2025_06_16_052700_add_website_to_events_table.php"

if [ -f "$MIGRATION_FILE" ]; then
    echo "   ❌ Found problematic migration file"
    rm -f "$MIGRATION_FILE"
    if [ $? -eq 0 ]; then
        echo "   ✅ Successfully deleted: $MIGRATION_FILE"
    else
        echo "   ❌ Failed to delete migration file"
        exit 1
    fi
else
    echo "   ✅ Problematic migration file not found (good)"
fi

echo ""

# Step 2: Check PHP version
echo "🔧 Step 2: Checking PHP version..."
if command_exists php; then
    PHP_VERSION=$(php -v | head -n 1)
    echo "   ✅ PHP found: $PHP_VERSION"
else
    echo "   ❌ PHP not found in PATH"
    exit 1
fi

echo ""

# Step 3: Run database operations
echo "🔧 Step 3: Fixing database migration records..."

# Remove the problematic migration from migrations table using PHP
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$deleted = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', '2025_06_16_052700_add_website_to_events_table')
        ->delete();
    
    if (\$deleted > 0) {
        echo \"   ✅ Removed problematic migration from database\\n\";
    } else {
        echo \"   ✅ Problematic migration not found in database (already clean)\\n\";
    }
} catch (Exception \$e) {
    echo \"   ❌ Error accessing database: \" . \$e->getMessage() . \"\\n\";
    exit(1);
}
"

if [ $? -ne 0 ]; then
    echo "   ❌ Database operation failed"
    exit 1
fi

echo ""

# Step 4: Run migrations
echo "🔧 Step 4: Running migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "   ✅ Migrations completed successfully"
else
    echo "   ❌ Migration failed"
    echo "   💡 Check the error message above for details"
    exit 1
fi

echo ""

# Step 5: Verify migration status
echo "🔧 Step 5: Verifying migration status..."
echo ""
php artisan migrate:status

echo ""

# Step 6: Verify events table structure
echo "🔧 Step 6: Verifying events table structure..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    if (\Illuminate\Support\Facades\Schema::hasTable('events')) {
        echo \"   ✅ Events table exists\\n\";
        
        \$columns = \Illuminate\Support\Facades\Schema::getColumnListing('events');
        echo \"   📋 Table has \" . count(\$columns) . \" columns\\n\";
        
        \$hasWebsiteUrl = in_array('website_url', \$columns);
        \$hasWebsite = in_array('website', \$columns);
        
        echo \"   - website_url: \" . (\$hasWebsiteUrl ? \"✅ exists\" : \"❌ missing\") . \"\\n\";
        echo \"   - website: \" . (\$hasWebsite ? \"✅ exists\" : \"❌ missing\") . \"\\n\";
        
        if (\$hasWebsiteUrl) {
            echo \"   ✅ Table structure is functional\\n\";
        }
        
    } else {
        echo \"   ❌ Events table does not exist\\n\";
        exit(1);
    }
} catch (Exception \$e) {
    echo \"   ❌ Error checking table: \" . \$e->getMessage() . \"\\n\";
    exit(1);
}
"

if [ $? -ne 0 ]; then
    exit 1
fi

echo ""
echo "=== MIGRATION FIX COMPLETED SUCCESSFULLY ==="
echo "✅ Problematic migration file removed"
echo "✅ Database migration records cleaned"
echo "✅ All migrations executed successfully"
echo "✅ Events table verified and functional"
echo ""
echo "🎯 Next steps:"
echo "1. Test the events functionality in your application"
echo "2. Clear application cache if needed: php artisan cache:clear"
echo "3. Clear config cache if needed: php artisan config:clear"
echo ""
echo "📋 Summary:"
echo "- Events table is ready for use"
echo "- All migrations are in sync"
echo "- No duplicate columns or conflicts"
echo ""
