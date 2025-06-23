<?php
/**
 * Download TinyMCE locally to avoid API key warnings
 */

echo "📥 Downloading TinyMCE locally to avoid API key warnings...\n";
echo "========================================================\n\n";

$tinymcePath = 'public/js/tinymce/';
$tinymceVersion = '6.8.6';
$downloadUrl = "https://download.tiny.cloud/tinymce/community/tinymce_$tinymceVersion.zip";

// Create directory if it doesn't exist
if (!is_dir($tinymcePath)) {
    mkdir($tinymcePath, 0755, true);
    echo "✅ Created TinyMCE directory: $tinymcePath\n";
}

// Check if TinyMCE is already downloaded
if (file_exists($tinymcePath . 'tinymce.min.js')) {
    echo "✅ TinyMCE already exists locally\n";
    echo "📍 Location: $tinymcePath\n";
    echo "💡 You can now update create.blade.php to use local version\n\n";
    
    echo "🔧 NEXT STEPS:\n";
    echo "=============\n";
    echo "1. Replace CDN link in create.blade.php:\n";
    echo "   FROM: https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js\n";
    echo "   TO:   {{ asset('js/tinymce/tinymce.min.js') }}\n\n";
    echo "2. This will eliminate the API key warning completely\n";
    echo "3. All template buttons will continue to work\n";
    
    exit(0);
}

echo "ℹ️  TinyMCE not found locally. Manual download required.\n\n";

echo "📋 MANUAL INSTALLATION INSTRUCTIONS:\n";
echo "====================================\n";
echo "1. Download TinyMCE Community Edition from:\n";
echo "   https://www.tiny.cloud/get-tiny/community/\n\n";
echo "2. Extract the zip file\n\n";
echo "3. Copy the 'tinymce' folder contents to:\n";
echo "   " . realpath($tinymcePath) . "\n\n";
echo "4. Update create.blade.php CDN link to:\n";
echo "   <script src=\"{{ asset('js/tinymce/tinymce.min.js') }}\"></script>\n\n";

echo "🎯 ALTERNATIVE QUICK SOLUTIONS:\n";
echo "===============================\n";
echo "1. **Get Free API Key** (Easiest):\n";
echo "   - Visit: https://www.tiny.cloud/auth/signup/\n";
echo "   - Sign up for free account\n";
echo "   - Replace 'no-api-key' with your actual key\n\n";

echo "2. **Suppress Warning** (Added to config):\n";
echo "   - Added 'promotion: false, branding: false' to config\n";
echo "   - This should reduce warnings\n\n";

echo "3. **Use Alternative CDN**:\n";
echo "   - jsdelivr: https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js\n";
echo "   - unpkg: https://unpkg.com/tinymce@6/tinymce.min.js\n\n";

echo "✅ Instructions complete!\n";
?>
