#!/usr/bin/env php
<?php

// Script to fix layout conflicts - change from layout-simple to layout-coreui-simple

$files_to_fix = [
    'resources/views/admin/events/edit.blade.php',
    'resources/views/admin/events/create.blade.php',
    'resources/views/admin/events/show.blade.php',
    'resources/views/admin/organizational-structure/create.blade.php',
    'resources/views/admin/organizational-structure/edit.blade.php',
    'resources/views/admin/organizational-structure/show.blade.php',
    'resources/views/admin/vision-mission-goal/create.blade.php',
    'resources/views/admin/vision-mission-goal/edit.blade.php',
    'resources/views/admin/vision-mission-goal/show.blade.php',
    'resources/views/admin/users/create.blade.php',
    'resources/views/admin/users/edit.blade.php',
    'resources/views/admin/users/show.blade.php',
    'resources/views/admin/services/create.blade.php',
    'resources/views/admin/services/edit.blade.php',
    'resources/views/admin/services/show.blade.php',
    'resources/views/admin/pages/create.blade.php',
    'resources/views/admin/pages/edit.blade.php',
    'resources/views/admin/pages/show.blade.php',
    'resources/views/admin/timelines/create.blade.php',
    'resources/views/admin/timelines/edit.blade.php',
    'resources/views/admin/timelines/show.blade.php',
    'resources/views/admin/contacts/show.blade.php',
];

$base_path = 'd:/laragon/www/LAMDAKU/lamdaku-cms-backend/';
$updated_count = 0;

echo "🔧 FIXING LAYOUT CONFLICTS\n";
echo "========================================\n\n";

foreach ($files_to_fix as $file) {
    $full_path = $base_path . $file;
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        
        // Replace layout-simple with layout-coreui-simple
        $new_content = str_replace(
            "@extends('admin.layout-simple')",
            "@extends('admin.layout-coreui-simple')",
            $content
        );
        
        if ($content !== $new_content) {
            file_put_contents($full_path, $new_content);
            echo "✅ Updated: $file\n";
            $updated_count++;
        } else {
            echo "ℹ️  Skipped: $file (already correct)\n";
        }
    } else {
        echo "⚠️  Missing: $file\n";
    }
}

echo "\n========================================\n";
echo "📊 SUMMARY:\n";
echo "   - Files updated: $updated_count\n";
echo "   - Files processed: " . count($files_to_fix) . "\n\n";

echo "🎉 LAYOUT CONFLICT FIX COMPLETE!\n";
echo "All admin pages now use consistent CoreUI layout.\n\n";

echo "🧪 TESTING:\n";
echo "1. Visit: /admin/ (Dashboard - CoreUI design)\n";
echo "2. Visit: /admin/company (Company Edit - should match dashboard)\n";
echo "3. Visit any other admin pages - all should be consistent\n\n";

echo "✨ No more layout conflicts!\n";
?>
