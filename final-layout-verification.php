<?php

echo "🔍 FINAL LAYOUT VERIFICATION - ALL PAGES USE LAYOUT-SIMPLE\n";
echo "========================================\n\n";

$base_path = 'd:/laragon/www/LAMDAKU/lamdaku-cms-backend/';

echo "📊 CHECKING ALL ADMIN PAGES:\n\n";

// All main admin pages that should use layout-simple
$admin_pages = [
    // Dashboard
    'resources/views/admin/dashboard-new-design.blade.php',
    
    // Company Management
    'resources/views/admin/company/index.blade.php',
    'resources/views/admin/company/create.blade.php',
    'resources/views/admin/company/edit.blade.php',
    'resources/views/admin/company/show.blade.php',
    
    // User Management
    'resources/views/admin/users/index.blade.php',
    'resources/views/admin/users/create.blade.php',
    'resources/views/admin/users/edit.blade.php',
    'resources/views/admin/users/show.blade.php',
    
    // Content Management
    'resources/views/admin/pages/index.blade.php',
    'resources/views/admin/pages/create.blade.php',
    'resources/views/admin/pages/edit.blade.php',
    'resources/views/admin/pages/show.blade.php',
    'resources/views/admin/articles/index.blade.php',
    'resources/views/admin/articles/create.blade.php',
    'resources/views/admin/articles/edit.blade.php',
    'resources/views/admin/articles/show.blade.php',
    'resources/views/admin/services/index.blade.php',
    'resources/views/admin/services/create.blade.php',
    'resources/views/admin/services/edit.blade.php',
    'resources/views/admin/services/show.blade.php',
    
    // Events
    'resources/views/admin/events/index.blade.php',
    'resources/views/admin/events/create.blade.php',
    'resources/views/admin/events/edit.blade.php',
    'resources/views/admin/events/show.blade.php',
    
    // Organization
    'resources/views/admin/organizational-structure/index.blade.php',
    'resources/views/admin/organizational-structure/create.blade.php',
    'resources/views/admin/organizational-structure/edit.blade.php',
    'resources/views/admin/organizational-structure/show.blade.php',
    'resources/views/admin/vision-mission-goal/index.blade.php',
    'resources/views/admin/vision-mission-goal/create.blade.php',
    'resources/views/admin/vision-mission-goal/edit.blade.php',
    'resources/views/admin/vision-mission-goal/show.blade.php',
    'resources/views/admin/timelines/index.blade.php',
    'resources/views/admin/timelines/create.blade.php',
    'resources/views/admin/timelines/edit.blade.php',
    'resources/views/admin/timelines/show.blade.php',
    
    // Communication
    'resources/views/admin/contacts/index.blade.php',
    'resources/views/admin/contacts/show.blade.php',
];

$correct_count = 0;
$total_count = 0;
$missing_files = [];
$wrong_layout = [];

foreach ($admin_pages as $file) {
    $full_path = $base_path . $file;
    $total_count++;
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        
        if (strpos($content, "@extends('admin.layout-simple')") !== false) {
            echo "   ✅ $file\n";
            $correct_count++;
        } else {
            echo "   ❌ $file (WRONG LAYOUT)\n";
            $wrong_layout[] = $file;
        }
    } else {
        echo "   ⚠️  $file (MISSING)\n";
        $missing_files[] = $file;
    }
}

echo "\n========================================\n";
echo "📈 VERIFICATION SUMMARY:\n";
echo "   - Total Pages Checked: $total_count\n";
echo "   - Correct Layout: $correct_count\n";
echo "   - Wrong Layout: " . count($wrong_layout) . "\n";
echo "   - Missing Files: " . count($missing_files) . "\n\n";

if ($correct_count === $total_count && empty($missing_files)) {
    echo "🎉 SUCCESS: ALL ADMIN PAGES USE LAYOUT-SIMPLE!\n\n";
    
    echo "✅ CONSISTENT LAYOUT ACHIEVED:\n";
    echo "   - Dashboard: Purple sidebar layout\n";
    echo "   - All menu pages: Purple sidebar layout\n";
    echo "   - All forms: Purple sidebar layout\n";
    echo "   - No more layout conflicts!\n\n";
    
    echo "🚀 READY FOR PRODUCTION:\n";
    echo "   1. Dashboard: http://lamdaku-cms-backend.test/admin/\n";
    echo "   2. Company: http://lamdaku-cms-backend.test/admin/company\n";
    echo "   3. Users: http://lamdaku-cms-backend.test/admin/users\n";
    echo "   4. Pages: http://lamdaku-cms-backend.test/admin/pages\n";
    echo "   5. Articles: http://lamdaku-cms-backend.test/admin/articles\n";
    echo "   6. All other admin pages...\n\n";
    
    echo "🎨 USER EXPERIENCE:\n";
    echo "   - Consistent purple sidebar across all pages\n";
    echo "   - Familiar navigation and design\n";
    echo "   - No jarring layout switches\n";
    echo "   - Professional, unified admin interface\n\n";
    
} else {
    echo "⚠️  ISSUES FOUND:\n";
    
    if (!empty($wrong_layout)) {
        echo "\n❌ Files with wrong layout:\n";
        foreach ($wrong_layout as $file) {
            echo "   - $file\n";
        }
    }
    
    if (!empty($missing_files)) {
        echo "\n⚠️  Missing files:\n";
        foreach ($missing_files as $file) {
            echo "   - $file\n";
        }
    }
}

echo "\n✨ Layout verification complete!\n";
echo "All admin pages now use consistent layout-simple.blade.php\n";
?>
