<?php
echo "=== DIRECT UPLOAD TEST ===\n";

// Test permission dan file operation langsung
$logoDir = 'D:\\laragon\\www\\LAMDAKU\\lamdaku-cms-backend\\storage\\app\\public\\logos';

echo "Logos directory: $logoDir\n";
echo "Directory exists: " . (is_dir($logoDir) ? 'YES' : 'NO') . "\n";
echo "Directory writable: " . (is_writable($logoDir) ? 'YES' : 'NO') . "\n";

// Create test file dengan berbagai method
$testFileName = 'test_' . time() . '.txt';
$testContent = 'Test upload at ' . date('Y-m-d H:i:s');

echo "\nTesting file creation methods:\n";

// Method 1: file_put_contents
$method1Path = $logoDir . '\\' . $testFileName;
$result1 = file_put_contents($method1Path, $testContent);
echo "1. file_put_contents: " . ($result1 !== false ? 'SUCCESS' : 'FAILED') . "\n";
if ($result1 !== false) {
    echo "   File exists: " . (file_exists($method1Path) ? 'YES' : 'NO') . "\n";
    echo "   File size: " . filesize($method1Path) . " bytes\n";
    unlink($method1Path); // cleanup
}

// Method 2: fopen/fwrite
$method2Path = $logoDir . '\\test2_' . time() . '.txt';
$handle = fopen($method2Path, 'w');
if ($handle) {
    $result2 = fwrite($handle, $testContent);
    fclose($handle);
    echo "2. fopen/fwrite: " . ($result2 !== false ? 'SUCCESS' : 'FAILED') . "\n";
    if ($result2 !== false) {
        echo "   File exists: " . (file_exists($method2Path) ? 'YES' : 'NO') . "\n";
        echo "   File size: " . filesize($method2Path) . " bytes\n";
        unlink($method2Path); // cleanup
    }
} else {
    echo "2. fopen/fwrite: FAILED to open file\n";
}

// Method 3: copy dari temp file
$tempFile = tempnam(sys_get_temp_dir(), 'upload_test');
file_put_contents($tempFile, $testContent);

$method3Path = $logoDir . '\\test3_' . time() . '.txt';
$result3 = copy($tempFile, $method3Path);
echo "3. copy: " . ($result3 ? 'SUCCESS' : 'FAILED') . "\n";
if ($result3) {
    echo "   File exists: " . (file_exists($method3Path) ? 'YES' : 'NO') . "\n";
    echo "   File size: " . filesize($method3Path) . " bytes\n";
    unlink($method3Path); // cleanup
}
unlink($tempFile); // cleanup temp

echo "\n=== END TEST ===\n";
