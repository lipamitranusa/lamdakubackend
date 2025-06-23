<?php
/**
 * Script untuk mengecek dan mengatasi masalah favicon cache
 * antara akses localhost dan IP address
 */

echo "=== LAMDAKU CMS - Favicon Cache Test ===\n";
echo "Tanggal: " . date('Y-m-d H:i:s') . "\n\n";

// Cek file favicon
$publicPath = __DIR__ . '/public';
$faviconFiles = [
    'favicon.ico',
    'favicon.png',
    'favicon.svg',
    'favicon-16x16.svg'
];

echo "📁 Cek File Favicon:\n";
foreach ($faviconFiles as $file) {
    $filePath = $publicPath . '/' . $file;
    if (file_exists($filePath)) {
        $size = filesize($filePath);
        $lastModified = date('Y-m-d H:i:s', filemtime($filePath));
        echo "✅ $file - " . number_format($size) . " bytes (Modified: $lastModified)\n";
    } else {
        echo "❌ $file - File tidak ditemukan\n";
    }
}

echo "\n";

// Generate cache busting URLs
$timestamp = time();
echo "🔄 Cache Busting URLs:\n";
echo "Localhost: http://localhost:8000/favicon.ico?v=$timestamp\n";
echo "IP Access: http://192.168.40.221:8000/favicon.ico?v=$timestamp\n";
echo "\n";

// Cek konfigurasi Laravel
echo "⚙️ Konfigurasi Laravel:\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if (preg_match('/APP_URL=(.+)/', $envContent, $matches)) {
        echo "APP_URL: " . trim($matches[1]) . "\n";
    }
    if (preg_match('/SESSION_DOMAIN=(.*)/', $envContent, $matches)) {
        $sessionDomain = trim($matches[1]);
        echo "SESSION_DOMAIN: " . ($sessionDomain ?: 'null/empty') . "\n";
    }
} else {
    echo "❌ File .env tidak ditemukan\n";
}

echo "\n";

// Test URL responses
echo "🌐 Test URL Responses:\n";
$testUrls = [
    'http://localhost:8000/favicon.ico',
    'http://192.168.40.221:8000/favicon.ico'
];

foreach ($testUrls as $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ $url - HTTP $httpCode (OK)\n";
    } else {
        echo "❌ $url - HTTP $httpCode (Error)\n";
    }
}

echo "\n";

// Recommendations
echo "💡 Rekomendasi untuk mengatasi masalah favicon:\n";
echo "\n";
echo "1. HARD REFRESH BROWSER:\n";
echo "   - Chrome/Firefox: Ctrl+Shift+R atau Ctrl+F5\n";
echo "   - Atau buka Developer Tools (F12) → Network → Disable cache\n";
echo "\n";
echo "2. CLEAR BROWSER CACHE:\n";
echo "   - Chrome: Settings → Privacy → Clear browsing data\n";
echo "   - Firefox: Settings → Privacy & Security → Clear Data\n";
echo "\n";
echo "3. TEST DALAM INCOGNITO/PRIVATE MODE:\n";
echo "   - Buka browser dalam mode incognito\n";
echo "   - Test kedua URL (localhost dan IP)\n";
echo "\n";
echo "4. MANUAL CACHE CLEAR:\n";
echo "   - Akses: chrome://settings/clearBrowserData\n";
echo "   - Pilih 'Cached images and files'\n";
echo "   - Clear data\n";
echo "\n";
echo "5. RESTART BROWSER:\n";
echo "   - Tutup semua tab browser\n";
echo "   - Restart aplikasi browser\n";
echo "   - Test kembali\n";
echo "\n";

echo "🔧 Commands untuk Laravel:\n";
echo "php artisan config:clear\n";
echo "php artisan config:cache\n";
echo "php artisan serve --host=0.0.0.0 --port=8000\n";
echo "\n";

echo "=== Test Selesai ===\n";
?>
