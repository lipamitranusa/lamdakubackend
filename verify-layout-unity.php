<?php

echo "🔍 FINAL LAYOUT VERIFICATION\n";
echo "========================================\n\n";

$base_path = 'd:/laragon/www/LAMDAKU/lamdaku-cms-backend/';

// Files that should use layout-simple (ALL admin pages)
$admin_files = [
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
    'resources/views/admin/services/index.blade.php',
    'resources/views/admin/services/create.blade.php',
    'resources/views/admin/services/edit.blade.php',
    'resources/views/admin/services/show.blade.php',
    
    // Event Management
    'resources/views/admin/events/index.blade.php',
    'resources/views/admin/events/create.blade.php',
    'resources/views/admin/events/edit.blade.php',
    
    // Organization
    'resources/views/admin/organizational-structure/index.blade.php',
    'resources/views/admin/organizational-structure/create.blade.php',
    'resources/views/admin/organizational-structure/edit.blade.php',
    'resources/views/admin/vision-mission-goal/index.blade.php',
    'resources/views/admin/timelines/index.blade.php',
    
    // Communication
    'resources/views/admin/contacts/index.blade.php',
    'resources/views/admin/contacts/show.blade.php',
];

echo "💜 LAYOUT-SIMPLE VERIFICATION:\n";
$correct_count = 0;
$total_count = count($admin_files);

foreach ($admin_files as $file) {
    $full_path = $base_path . $file;
    $filename = basename($file);
    $folder = dirname(str_replace('resources/views/admin/', '', $file));
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        if (strpos($content, "@extends('admin.layout-simple')") !== false) {
            echo "   ✅ $folder/$filename\n";
            $correct_count++;
        } else if (strpos($content, "@extends('admin.layout-coreui-simple')") !== false) {
            echo "   ❌ $folder/$filename (USING COREUI-SIMPLE)\n";
        } else if (strpos($content, "@extends('admin.layout-coreui')") !== false) {
            echo "   ❌ $folder/$filename (USING COREUI)\n";
        } else {
            echo "   ⚠️  $folder/$filename (NO LAYOUT FOUND)\n";
        }
    } else {
        echo "   📁 $folder/$filename (FILE NOT FOUND)\n";
    }
}

echo "\n========================================\n";
echo "📊 VERIFICATION SUMMARY:\n";
echo "   - Total admin files checked: $total_count\n";
echo "   - Using layout-simple: $correct_count\n";
echo "   - Consistency rate: " . round(($correct_count / $total_count) * 100, 1) . "%\n\n";

if ($correct_count === $total_count) {
    echo "🎉 PERFECT! ALL ADMIN PAGES USE LAYOUT-SIMPLE!\n\n";
    echo "✅ BENEFITS ACHIEVED:\n";
    echo "   - 100% layout consistency\n";
    echo "   - Unified purple theme across all pages\n";
    echo "   - No more layout conflicts\n";
    echo "   - Seamless user experience\n";
    echo "   - Easy maintenance with single layout\n\n";
    
    echo "🚀 READY FOR PRODUCTION:\n";
    echo "   1. Dashboard: Purple sidebar ✅\n";
    echo "   2. All menu pages: Purple sidebar ✅\n";
    echo "   3. All form pages: Purple sidebar ✅\n";
    echo "   4. Navigation: Consistent everywhere ✅\n\n";
    
    echo "🎨 USER EXPERIENCE:\n";
    echo "   - Professional purple theme\n";
    echo "   - Familiar navigation structure\n";
    echo "   - No jarring layout transitions\n";
    echo "   - Consistent branding throughout\n\n";
    
    echo "✨ Layout unity achieved! LAMDAKU admin is now perfectly consistent!\n";
} else {
    $remaining = $total_count - $correct_count;
    echo "⚠️  ATTENTION: $remaining files still need layout updates\n";
    echo "Please check the files marked with ❌ above\n\n";
    
    echo "🔧 NEXT STEPS:\n";
    echo "   1. Update remaining files to use layout-simple\n";
    echo "   2. Clear view cache: php artisan view:clear\n";
    echo "   3. Test all admin pages for consistency\n";
}

echo "\n📋 TESTING CHECKLIST:\n";
echo "   □ Visit /admin/ (dashboard)\n";
echo "   □ Visit /admin/company (company management)\n";
echo "   □ Visit /admin/company/.../edit (company edit)\n";
echo "   □ Visit /admin/users (user management)\n";
echo "   □ Visit /admin/pages (pages management)\n";
echo "   □ Visit /admin/articles (articles management)\n";
echo "   □ Visit /admin/services (services management)\n";
echo "   □ Visit /admin/events (events management)\n";
echo "   □ Visit /admin/contacts (contacts)\n\n";

echo "🎯 Expected: All pages should have identical purple sidebar!\n";
?>
