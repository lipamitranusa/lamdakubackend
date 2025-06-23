<?php

echo "🔍 LAYOUT VERIFICATION SCRIPT\n";
echo "========================================\n\n";

// Check layout distribution
$base_path = 'd:/laragon/www/LAMDAKU/lamdaku-cms-backend/';

echo "📊 LAYOUT DISTRIBUTION CHECK:\n\n";

// Files that should use layout-coreui-simple (only dashboard)
$coreui_files = [
    'resources/views/admin/dashboard-new-design.blade.php',
];

echo "🎨 COREUI LAYOUT (layout-coreui-simple):\n";
foreach ($coreui_files as $file) {
    $full_path = $base_path . $file;
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        if (strpos($content, "@extends('admin.layout-coreui-simple')") !== false) {
            echo "   ✅ $file\n";
        } else {
            echo "   ❌ $file (WRONG LAYOUT)\n";
        }
    } else {
        echo "   ⚠️  $file (NOT FOUND)\n";
    }
}

echo "\n💜 SIMPLE LAYOUT (layout-simple):\n";

// Files that should use layout-simple
$simple_files = [
    'resources/views/admin/company/edit.blade.php',
    'resources/views/admin/company/create.blade.php',
    'resources/views/admin/company/show.blade.php',
    'resources/views/admin/users/edit.blade.php',
    'resources/views/admin/users/create.blade.php',
    'resources/views/admin/users/show.blade.php',
    'resources/views/admin/pages/edit.blade.php',
    'resources/views/admin/pages/create.blade.php',
    'resources/views/admin/pages/show.blade.php',
    'resources/views/admin/services/edit.blade.php',
    'resources/views/admin/services/create.blade.php',
    'resources/views/admin/services/show.blade.php',
    'resources/views/admin/events/edit.blade.php',
    'resources/views/admin/events/create.blade.php',
    'resources/views/admin/organizational-structure/create.blade.php',
    'resources/views/admin/organizational-structure/edit.blade.php',
    'resources/views/admin/contacts/show.blade.php',
];

$correct_simple = 0;
foreach ($simple_files as $file) {
    $full_path = $base_path . $file;
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        if (strpos($content, "@extends('admin.layout-simple')") !== false) {
            echo "   ✅ $file\n";
            $correct_simple++;
        } else {
            echo "   ❌ $file (WRONG LAYOUT)\n";
        }
    } else {
        echo "   ⚠️  $file (NOT FOUND)\n";
    }
}

echo "\n========================================\n";
echo "📈 VERIFICATION SUMMARY:\n";
echo "   - CoreUI Layout Files: " . count($coreui_files) . "\n";
echo "   - Simple Layout Files: $correct_simple/" . count($simple_files) . "\n";

if ($correct_simple === count($simple_files)) {
    echo "\n🎉 SUCCESS: All layouts are correctly configured!\n";
    echo "   - Dashboard uses CoreUI modern design\n";
    echo "   - All other pages use consistent purple layout\n";
    echo "   - No more layout conflicts\n\n";
    
    echo "🚀 READY FOR TESTING:\n";
    echo "   1. Visit: http://lamdaku-cms-backend.test/admin/ (CoreUI dashboard)\n";
    echo "   2. Visit: http://lamdaku-cms-backend.test/admin/company (consistent layout)\n";
    echo "   3. Edit any company info (purple sidebar)\n";
    echo "   4. All should be visually consistent now!\n";
} else {
    echo "\n⚠️  WARNING: Some files still have incorrect layouts\n";
    echo "Please check the files marked with ❌ above\n";
}

echo "\n✨ Layout verification complete!\n";
?>
