<?php
// Debug Upload Test
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== STORAGE DEBUG TEST ===\n";

// Test 1: Check storage disk configuration
echo "1. Storage disk 'public' configuration:\n";
$config = config('filesystems.disks.public');
print_r($config);

// Test 2: Check if public disk is accessible
echo "\n2. Testing public disk access:\n";
try {
    $files = Storage::disk('public')->files('logos');
    echo "Files in storage/app/public/logos: " . count($files) . " files\n";
    foreach ($files as $file) {
        echo "- $file\n";
    }
} catch (Exception $e) {
    echo "Error accessing public disk: " . $e->getMessage() . "\n";
}

// Test 3: Check storage path
echo "\n3. Storage paths:\n";
echo "storage_path('app/public'): " . storage_path('app/public') . "\n";
echo "public_path('storage'): " . public_path('storage') . "\n";
echo "Storage logos path: " . storage_path('app/public/logos') . "\n";

// Test 4: Check if directories exist
echo "\n4. Directory existence check:\n";
echo "storage/app/public exists: " . (is_dir(storage_path('app/public')) ? 'YES' : 'NO') . "\n";
echo "storage/app/public/logos exists: " . (is_dir(storage_path('app/public/logos')) ? 'YES' : 'NO') . "\n";
echo "public/storage exists: " . (is_dir(public_path('storage')) ? 'YES' : 'NO') . "\n";
echo "public/storage/logos exists: " . (is_dir(public_path('storage/logos')) ? 'YES' : 'NO') . "\n";

// Test 5: Check permissions
echo "\n5. Directory permissions:\n";
$logoDir = storage_path('app/public/logos');
if (is_dir($logoDir)) {
    echo "Logos directory permissions: " . substr(sprintf('%o', fileperms($logoDir)), -4) . "\n";
    echo "Is writable: " . (is_writable($logoDir) ? 'YES' : 'NO') . "\n";
}

// Test 6: Try to create a test file
echo "\n6. Test file creation:\n";
try {
    $testContent = "Test file created at " . date('Y-m-d H:i:s');
    $stored = Storage::disk('public')->put('logos/test-upload.txt', $testContent);
    if ($stored) {
        echo "✓ Test file created successfully\n";
        
        // Check if it exists
        if (Storage::disk('public')->exists('logos/test-upload.txt')) {
            echo "✓ Test file exists in storage\n";
        } else {
            echo "✗ Test file NOT found in storage\n";
        }
        
        // Clean up
        Storage::disk('public')->delete('logos/test-upload.txt');
        echo "✓ Test file cleaned up\n";
    } else {
        echo "✗ Failed to create test file\n";
    }
} catch (Exception $e) {
    echo "✗ Error creating test file: " . $e->getMessage() . "\n";
}

echo "\n=== END DEBUG ===\n";
