<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== UPLOAD TEST ===\n";

// Create a test image file
$testImagePath = __DIR__ . '/test-upload-image.png';

// Create a simple PNG file (1x1 pixel)
$img = imagecreate(100, 100);
$white = imagecolorallocate($img, 255, 255, 255);
imagepng($img, $testImagePath);
imagedestroy($img);

echo "Created test image: $testImagePath\n";
echo "File size: " . filesize($testImagePath) . " bytes\n";

// Test 1: Direct storage test
echo "\n=== Test 1: Direct Storage ===\n";
try {
    $content = file_get_contents($testImagePath);
    $storedPath = Storage::disk('public')->put('logos/direct-test.png', $content);
    
    if ($storedPath) {
        echo "✓ Direct storage successful: $storedPath\n";
        
        // Check if file exists
        $fullPath = storage_path('app/public/' . $storedPath);
        echo "File exists at $fullPath: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        
        // Clean up
        Storage::disk('public')->delete($storedPath);
        echo "✓ Cleaned up test file\n";
    } else {
        echo "✗ Direct storage failed\n";
    }
} catch (Exception $e) {
    echo "✗ Direct storage error: " . $e->getMessage() . "\n";
}

// Test 2: UploadedFile simulation
echo "\n=== Test 2: UploadedFile Simulation ===\n";
try {
    // Simulate uploaded file
    $uploadedFile = new UploadedFile(
        $testImagePath,
        'test-logo.png',
        'image/png',
        null,
        true // test mode
    );
    
    echo "Created UploadedFile simulation\n";
    echo "Original name: " . $uploadedFile->getClientOriginalName() . "\n";
    echo "Mime type: " . $uploadedFile->getMimeType() . "\n";
    echo "Size: " . $uploadedFile->getSize() . " bytes\n";
    
    // Test storage like in controller
    $logoName = time() . '_' . $uploadedFile->getClientOriginalName();
    echo "Generated name: $logoName\n";
    
    $stored = $uploadedFile->storeAs('public/logos', $logoName);
    
    if ($stored) {
        echo "✓ UploadedFile storage successful: $stored\n";
        
        // Verify file exists
        $fullPath = storage_path('app/public/logos/' . $logoName);
        echo "File exists at $fullPath: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        
        if (file_exists($fullPath)) {
            echo "File size: " . filesize($fullPath) . " bytes\n";
        }
        
        // Clean up
        Storage::disk('public')->delete('logos/' . $logoName);
        echo "✓ Cleaned up test file\n";
    } else {
        echo "✗ UploadedFile storage failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ UploadedFile error: " . $e->getMessage() . "\n";
}

// Clean up test image
unlink($testImagePath);
echo "\n✓ Cleaned up test image\n";

echo "\n=== END TEST ===\n";
