#!/usr/bin/env php
<?php

// Script to revert all layout changes back to layout-simple

echo "🔄 REVERTING LAYOUT CHANGES\n";
echo "========================================\n\n";

$base_path = 'd:/laragon/www/LAMDAKU/lamdaku-cms-backend/';

// Files that were changed to layout-coreui-simple, revert them back
$files_to_revert = [
    'resources/views/admin/company/edit.blade.php',
    'resources/views/admin/company/create.blade.php', 
    'resources/views/admin/company/show.blade.php',
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

$reverted_count = 0;

foreach ($files_to_revert as $file) {
    $full_path = $base_path . $file;
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        
        // Revert back to layout-simple
        $new_content = str_replace(
            "@extends('admin.layout-coreui-simple')",
            "@extends('admin.layout-simple')",
            $content
        );
        
        if ($content !== $new_content) {
            file_put_contents($full_path, $new_content);
            echo "✅ Reverted: $file\n";
            $reverted_count++;
        } else {
            echo "ℹ️  Already correct: $file\n";
        }
    } else {
        echo "⚠️  Missing: $file\n";
    }
}

echo "\n========================================\n";
echo "📊 REVERT SUMMARY:\n";
echo "   - Files reverted: $reverted_count\n";
echo "   - Files processed: " . count($files_to_revert) . "\n\n";

echo "🎉 LAYOUT REVERT COMPLETE!\n";
echo "All admin pages now use layout-simple consistently.\n\n";

echo "🎨 RESULT:\n";
echo "- Dashboard: layout-coreui-simple (new CoreUI design)\n";
echo "- All other pages: layout-simple (original purple sidebar)\n\n";

echo "✨ Consistent layout restored!\n";
?>
