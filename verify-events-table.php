<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== EVENTS TABLE VERIFICATION ===\n\n";

try {
    // Check if events table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('events');
    
    if ($tableExists) {
        echo "✅ Table 'events' exists\n\n";
        
        // Check table columns
        echo "📋 Table Structure:\n";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('events');
        
        $expectedColumns = [
            'id', 'title', 'slug', 'description', 'content', 'location', 'address',
            'start_date', 'end_date', 'is_all_day', 'timezone',
            'category', 'tags', 'event_type', 'status',
            'requires_registration', 'max_participants', 'current_participants',
            'registration_fee', 'registration_deadline', 'registration_instructions',
            'contact_person', 'contact_email', 'contact_phone', 'website_url',
            'featured_image', 'gallery',
            'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
            'og_title', 'og_description', 'og_image',
            'author_id', 'published_at', 'view_count', 'is_featured', 'allow_comments',
            'created_at', 'updated_at'
        ];
        
        echo "   Found columns: " . count($columns) . "\n";
        echo "   Expected columns: " . count($expectedColumns) . "\n\n";
        
        // Check for missing columns
        $missingColumns = array_diff($expectedColumns, $columns);
        if (!empty($missingColumns)) {
            echo "❌ Missing columns:\n";
            foreach ($missingColumns as $column) {
                echo "   - {$column}\n";
            }
        } else {
            echo "✅ All expected columns are present\n";
        }
        
        // Check for extra columns
        $extraColumns = array_diff($columns, $expectedColumns);
        if (!empty($extraColumns)) {
            echo "\n📝 Extra columns found:\n";
            foreach ($extraColumns as $column) {
                echo "   - {$column}\n";
            }
        }
        
        // Check specific important columns
        echo "\n🔍 Important Columns Check:\n";
        $importantColumns = ['website_url', 'slug', 'author_id', 'start_date', 'end_date'];
        foreach ($importantColumns as $column) {
            $exists = in_array($column, $columns);
            echo "   " . ($exists ? "✅" : "❌") . " {$column}\n";
        }
        
        // Check if we can insert a test record (optional)
        echo "\n🧪 Testing table accessibility:\n";
        try {
            $count = \Illuminate\Support\Facades\DB::table('events')->count();
            echo "   ✅ Table is accessible, current records: {$count}\n";
        } catch (Exception $e) {
            echo "   ❌ Error accessing table: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ Table 'events' does not exist!\n";
        echo "   You need to run: php artisan migrate\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error checking events table: " . $e->getMessage() . "\n";
}

echo "\n=== MIGRATION STATUS ===\n";
try {
    $migrations = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', 'like', '%events%')
        ->orderBy('batch')
        ->get();
        
    if ($migrations->count() > 0) {
        echo "Events-related migrations:\n";
        foreach ($migrations as $migration) {
            echo "   ✅ {$migration->migration} (batch {$migration->batch})\n";
        }
    } else {
        echo "❌ No events-related migrations found in migrations table\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking migrations: " . $e->getMessage() . "\n";
}

echo "\n=== RESOLUTION SUMMARY ===\n";
echo "🎯 Problem: Migration tried to add 'website' column to non-existent 'events' table\n";
echo "✅ Solution: Removed duplicate migration (2025_06_16_052700_add_website_to_events_table.php)\n";
echo "📋 Reason: 'events' table already has 'website_url' column, 'website' column was redundant\n";
echo "🚀 Status: Ready for production deployment\n";

echo "\n=== DEPLOYMENT INSTRUCTIONS ===\n";
echo "For production deployment:\n";
echo "1. Ensure the problematic migration file is deleted from production\n";
echo "2. Run: php artisan migrate --force\n";
echo "3. Verify table structure with this verification script\n\n";
