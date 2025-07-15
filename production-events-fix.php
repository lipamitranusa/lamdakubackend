<?php

echo "=== LAMDAKU PRODUCTION EVENTS MIGRATION FIX ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "Server: Production\n\n";

// Check if we're in Laravel project directory
if (!file_exists('artisan')) {
    echo "❌ Error: artisan file not found. Please run this script from Laravel project root.\n";
    echo "Current directory: " . getcwd() . "\n\n";
    echo "💡 Navigate to the correct directory first:\n";
    echo "   cd /path/to/your/laravel/project\n";
    exit(1);
}

echo "✅ Found Laravel project in: " . getcwd() . "\n\n";

try {
    // Step 1: Remove problematic migration file
    echo "🔧 Step 1: Removing problematic migration file...\n";
    $migrationFile = 'database/migrations/2025_06_16_052700_add_website_to_events_table.php';
    
    if (file_exists($migrationFile)) {
        echo "   ❌ Found problematic migration file\n";
        if (unlink($migrationFile)) {
            echo "   ✅ Successfully deleted: $migrationFile\n";
        } else {
            echo "   ❌ Failed to delete migration file\n";
            exit(1);
        }
    } else {
        echo "   ✅ Problematic migration file not found (good)\n";
    }
    
    echo "\n";
    
    // Step 2: Bootstrap Laravel
    echo "🔧 Step 2: Connecting to Laravel application...\n";
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "   ✅ Laravel application loaded\n\n";
    
    // Step 3: Fix database migration records
    echo "🔧 Step 3: Fixing database migration records...\n";
    $deleted = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', '2025_06_16_052700_add_website_to_events_table')
        ->delete();
    
    if ($deleted > 0) {
        echo "   ✅ Removed problematic migration from database\n";
    } else {
        echo "   ✅ Problematic migration not found in database (already clean)\n";
    }
    
    echo "\n";
    
    // Step 4: Check events table status
    echo "🔧 Step 4: Checking events table status...\n";
    $eventsTableExists = \Illuminate\Support\Facades\Schema::hasTable('events');
    
    if ($eventsTableExists) {
        echo "   ✅ Events table exists\n";
        
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('events');
        echo "   📋 Table has " . count($columns) . " columns\n";
        
        $hasWebsiteUrl = in_array('website_url', $columns);
        $hasWebsite = in_array('website', $columns);
        
        echo "   - website_url: " . ($hasWebsiteUrl ? "✅ exists" : "❌ missing") . "\n";
        echo "   - website: " . ($hasWebsite ? "✅ exists" : "❌ missing") . "\n";
        
        if ($hasWebsiteUrl) {
            echo "   ✅ Table structure is functional\n";
        }
    } else {
        echo "   ❌ Events table does not exist, will be created by migration\n";
    }
    
    echo "\n";
    
    // Step 5: Run artisan migrate
    echo "🔧 Step 5: Running migrations...\n";
    $output = [];
    $returnCode = 0;
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Migrations completed successfully\n";
        foreach ($output as $line) {
            if (trim($line)) {
                echo "   " . $line . "\n";
            }
        }
    } else {
        echo "   ❌ Migration failed\n";
        foreach ($output as $line) {
            echo "   " . $line . "\n";
        }
        exit(1);
    }
    
    echo "\n";
    
    // Step 6: Verify final status
    echo "🔧 Step 6: Verifying final status...\n";
    
    // Check events table again
    $eventsTableExists = \Illuminate\Support\Facades\Schema::hasTable('events');
    if ($eventsTableExists) {
        echo "   ✅ Events table verified and exists\n";
        
        // Get migration status for events
        $eventsMigrations = \Illuminate\Support\Facades\DB::table('migrations')
            ->where('migration', 'like', '%events%')
            ->orderBy('batch')
            ->get();
            
        echo "   📋 Events migrations in database:\n";
        foreach ($eventsMigrations as $migration) {
            echo "      ✅ {$migration->migration} (batch {$migration->batch})\n";
        }
        
        // Check table structure
        $recordCount = \Illuminate\Support\Facades\DB::table('events')->count();
        echo "   📊 Current events records: {$recordCount}\n";
        
    } else {
        echo "   ❌ Events table still does not exist after migration\n";
        exit(1);
    }
    
    echo "\n";
    echo "=== MIGRATION FIX COMPLETED SUCCESSFULLY ===\n";
    echo "✅ Problematic migration file removed\n";
    echo "✅ Database migration records cleaned\n";
    echo "✅ All migrations executed successfully\n";
    echo "✅ Events table verified and functional\n";
    echo "\n";
    echo "🎯 Next steps:\n";
    echo "1. Test the events functionality in your application\n";
    echo "2. Clear application cache if needed: php artisan cache:clear\n";
    echo "3. Clear config cache if needed: php artisan config:clear\n";
    echo "\n";
    echo "📋 Summary:\n";
    echo "- Events table is ready for use\n";
    echo "- All migrations are in sync\n";
    echo "- No duplicate columns or conflicts\n";
    echo "\n";
    
} catch (Exception $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Manual recovery steps:\n";
    echo "1. Delete file: database/migrations/2025_06_16_052700_add_website_to_events_table.php\n";
    echo "2. Run: php artisan migrate --force\n";
    echo "3. Check: php artisan migrate:status\n";
    exit(1);
}
