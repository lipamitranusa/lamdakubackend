<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRODUCTION EVENTS MIGRATION FIX ===\n\n";

try {
    // Step 1: Check if problematic migration exists in migrations table
    echo "🔍 Step 1: Checking problematic migration in database...\n";
    
    $problematicMigration = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', '2025_06_16_052700_add_website_to_events_table')
        ->first();
    
    if ($problematicMigration) {
        echo "   ❌ Found problematic migration in database (batch {$problematicMigration->batch})\n";
        echo "   🔧 Removing from migrations table...\n";
        
        \Illuminate\Support\Facades\DB::table('migrations')
            ->where('migration', '2025_06_16_052700_add_website_to_events_table')
            ->delete();
            
        echo "   ✅ Removed problematic migration from database\n";
    } else {
        echo "   ✅ Problematic migration not found in database\n";
    }
    
    // Step 2: Check if events table exists
    echo "\n🔍 Step 2: Checking events table...\n";
    $eventsTableExists = \Illuminate\Support\Facades\Schema::hasTable('events');
    
    if ($eventsTableExists) {
        echo "   ✅ Events table already exists\n";
        
        // Check if website column exists
        $hasWebsiteColumn = \Illuminate\Support\Facades\Schema::hasColumn('events', 'website');
        $hasWebsiteUrlColumn = \Illuminate\Support\Facades\Schema::hasColumn('events', 'website_url');
        
        echo "   📋 Column check:\n";
        echo "      - website_url: " . ($hasWebsiteUrlColumn ? "✅ exists" : "❌ missing") . "\n";
        echo "      - website: " . ($hasWebsiteColumn ? "✅ exists" : "❌ missing") . "\n";
        
        // If website column doesn't exist but website_url does, we're good
        if ($hasWebsiteUrlColumn && !$hasWebsiteColumn) {
            echo "   ✅ Table structure is correct (has website_url, no duplicate website)\n";
        } elseif ($hasWebsiteColumn && $hasWebsiteUrlColumn) {
            echo "   ⚠️  Both website and website_url columns exist (redundant)\n";
            echo "   💡 Consider removing 'website' column if not used\n";
        } elseif (!$hasWebsiteUrlColumn) {
            echo "   ❌ Missing website_url column\n";
        }
        
    } else {
        echo "   ❌ Events table does not exist\n";
        echo "   🔧 Need to run migration to create events table\n";
    }
    
    // Step 3: Check current migration status
    echo "\n🔍 Step 3: Checking migration status...\n";
    $createEventsMigration = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', '2025_06_16_100000_create_events_table')
        ->first();
        
    if ($createEventsMigration) {
        echo "   ✅ Create events table migration already ran (batch {$createEventsMigration->batch})\n";
    } else {
        echo "   ❌ Create events table migration not found\n";
        echo "   🔧 Need to run: php artisan migrate\n";
    }
    
    // Step 4: Verify migration files exist
    echo "\n🔍 Step 4: Checking migration files...\n";
    $migrationFiles = [
        'database/migrations/2025_06_16_100000_create_events_table.php' => 'Create events table',
        'database/migrations/2025_06_16_052700_add_website_to_events_table.php' => 'Add website column (should be deleted)'
    ];
    
    foreach ($migrationFiles as $file => $description) {
        if (file_exists($file)) {
            if (strpos($file, 'add_website_to_events') !== false) {
                echo "   ❌ {$description}: EXISTS (should be deleted)\n";
                echo "      🔧 Delete this file: {$file}\n";
            } else {
                echo "   ✅ {$description}: EXISTS\n";
            }
        } else {
            if (strpos($file, 'add_website_to_events') !== false) {
                echo "   ✅ {$description}: NOT FOUND (good, it was deleted)\n";
            } else {
                echo "   ❌ {$description}: NOT FOUND\n";
            }
        }
    }
    
    // Step 5: Show resolution steps
    echo "\n=== RESOLUTION STEPS FOR PRODUCTION ===\n";
    
    if (!$eventsTableExists) {
        echo "🎯 SCENARIO: Events table doesn't exist\n";
        echo "1. Delete problematic migration file (if exists):\n";
        echo "   rm database/migrations/2025_06_16_052700_add_website_to_events_table.php\n\n";
        echo "2. Run migration to create events table:\n";
        echo "   php artisan migrate --force\n\n";
        echo "3. Verify table was created correctly\n\n";
    } else {
        echo "🎯 SCENARIO: Events table exists\n";
        echo "1. Problematic migration already removed from database ✅\n";
        echo "2. Delete migration file from filesystem (if still exists):\n";
        echo "   rm database/migrations/2025_06_16_052700_add_website_to_events_table.php\n\n";
        echo "3. Run any pending migrations:\n";
        echo "   php artisan migrate --force\n\n";
    }
    
    echo "=== COMMANDS FOR SSH SESSION ===\n";
    echo "# Connect to server:\n";
    echo "ssh -p 65002 u329849080@37.44.245.60\n\n";
    echo "# Navigate to project directory (adjust path as needed):\n";
    echo "cd /path/to/your/laravel/project\n\n";
    echo "# Remove problematic migration file:\n";
    echo "rm -f database/migrations/2025_06_16_052700_add_website_to_events_table.php\n\n";
    echo "# Run migrations:\n";
    echo "php artisan migrate --force\n\n";
    echo "# Verify result:\n";
    echo "php artisan migrate:status\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Manual steps to fix:\n";
    echo "1. Connect to production server\n";
    echo "2. Delete: database/migrations/2025_06_16_052700_add_website_to_events_table.php\n";
    echo "3. Run: php artisan migrate --force\n";
}

echo "\n=== PREVENTION ===\n";
echo "📝 To prevent this in the future:\n";
echo "1. Always check if columns already exist before creating add_column migrations\n";
echo "2. Test migrations in staging environment first\n";
echo "3. Use proper timestamp ordering (earlier timestamp = runs first)\n";
echo "4. Review migration dependencies before deployment\n\n";
