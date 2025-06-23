<?php

echo "Testing logo paths...\n";

$logoFile = 'default-logo.svg';
$storagePath = 'storage/app/public/logos/' . $logoFile;
$publicPath = 'public/storage/logos/' . $logoFile;

echo "Logo file: " . $logoFile . "\n";
echo "Storage path: " . $storagePath . "\n";
echo "Public path: " . $publicPath . "\n";

echo "Storage exists: " . (file_exists($storagePath) ? 'YES' : 'NO') . "\n";
echo "Public exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";

$fullPath = __DIR__ . '/' . $publicPath;
echo "Full path: " . $fullPath . "\n";
echo "Full path exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
