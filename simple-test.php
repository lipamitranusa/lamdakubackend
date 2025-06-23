<?php
echo "=== SIMPLE STORAGE TEST ===\n";

// Check basic paths
$storagePath = __DIR__ . '/storage/app/public/logos';
$publicPath = __DIR__ . '/public/storage/logos';

echo "Storage path: $storagePath\n";
echo "Public path: $publicPath\n";

echo "Storage path exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Public path exists: " . (is_dir($publicPath) ? 'YES' : 'NO') . "\n";

if (is_dir($storagePath)) {
    echo "Storage path is writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";
    
    // List files
    $files = scandir($storagePath);
    echo "Files in storage:\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- $file\n";
        }
    }
}

if (is_dir($publicPath)) {
    echo "Public path is readable: " . (is_readable($publicPath) ? 'YES' : 'NO') . "\n";
    
    // List files
    $files = scandir($publicPath);
    echo "Files in public:\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- $file\n";
        }
    }
}

// Test file creation
$testFile = $storagePath . '/test-direct.txt';
echo "\nTesting direct file creation...\n";
$result = file_put_contents($testFile, 'Direct test file');
if ($result !== false) {
    echo "✓ Direct file creation successful\n";
    unlink($testFile);
    echo "✓ Test file cleaned up\n";
} else {
    echo "✗ Direct file creation failed\n";
}

echo "\n=== END TEST ===\n";
