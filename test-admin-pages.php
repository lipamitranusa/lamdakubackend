<?php
echo "=== LAMDAKU Admin Pages Layout Consistency Test ===\n\n";

// Check if all admin blade files use layout-simple
$adminViewsPath = 'resources/views/admin';
$directories = [
    'company',
    'users', 
    'articles',
    'services',
    'events',
    'contacts',
    'pages',
    'organizational-structure',
    'vision-mission-goal',
    'timelines'
];

$files = [
    'dashboard-new-design.blade.php'
];

// Check main dashboard file
foreach ($files as $file) {
    $filePath = $adminViewsPath . '/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $hasLayoutSimple = strpos($content, "@extends('admin.layout-simple')") !== false;
        echo "✓ " . $file . ": " . ($hasLayoutSimple ? "USING layout-simple" : "NOT using layout-simple") . "\n";
    }
}

echo "\n";

// Check directory files
foreach ($directories as $dir) {
    echo "=== $dir directory ===\n";
    $dirPath = $adminViewsPath . '/' . $dir;
    
    if (is_dir($dirPath)) {
        $bladeFiles = glob($dirPath . '/*.blade.php');
        
        foreach ($bladeFiles as $file) {
            $content = file_get_contents($file);
            $hasLayoutSimple = strpos($content, "@extends('admin.layout-simple')") !== false;
            $filename = basename($file);
            echo "  ✓ " . $filename . ": " . ($hasLayoutSimple ? "USING layout-simple" : "NOT using layout-simple") . "\n";
        }
    }
    echo "\n";
}

echo "=== Test completed ===\n";
