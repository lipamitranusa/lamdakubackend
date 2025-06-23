<?php
/**
 * TinyMCE Premium Features Check
 * Identifies features that require a valid API key
 */

echo "🔑 TinyMCE Premium Features Check\n";
echo "=================================\n\n";

// Read the advanced editor configuration
$configPath = 'public/js/advanced-editor.js';
if (!file_exists($configPath)) {
    echo "❌ Advanced editor config file not found at: $configPath\n";
    exit(1);
}

$configContent = file_get_contents($configPath);

// List of premium features that require API key
$premiumFeatures = [
    // Premium plugins
    'powerpaste',
    'checklist',
    'formatpainter',
    'pageembed',
    'permanentpen',
    'tinymcespellchecker',
    'a11ychecker',
    'linkchecker',
    'mentions',
    'tableofcontents',
    'footnotes',
    'autocorrect',
    'typography',
    'inlinecss',
    'exportpdf',
    'exportword',
    
    // Premium toolbar buttons/features
    'fontfamily',
    'fontsize',
    'fontsizeinput',
    'fontselect',
    'fontsizeselect',
    'styleselect',
    'forecolor',
    'backcolor',
    'casechange',
    'tableofcontents',
    'checklist',
    'formatpainter',
    'permanentpen',
    'pageembed'
];

// Premium options that require API key
$premiumOptions = [
    'font_family_formats',
    'font_size_formats',
    'fontsize_formats',
    'style_formats',
    'file_picker_callback',
    'file_picker_types',
    'images_upload_handler',
    'images_upload_url',
    'paste_preprocess',
    'powerpaste_',
    'checklist_',
    'formatpainter_',
    'permanentpen_',
    'a11ychecker_',
    'linkchecker_',
    'spellchecker_',
    'tinymcespellchecker_'
];

echo "1. 🔌 Checking for premium plugins/toolbar features:\n";
$foundPremiumFeatures = [];
foreach ($premiumFeatures as $feature) {
    if (preg_match("/['\"]" . $feature . "['\"]/", $configContent) || 
        preg_match("/" . $feature . "(?:\s|$|\|)/", $configContent)) {
        $foundPremiumFeatures[] = $feature;
        echo "   ❌ Found premium feature: $feature\n";
    }
}

if (empty($foundPremiumFeatures)) {
    echo "   ✅ No premium plugins/features found\n";
} else {
    echo "   ⚠️  Total premium features found: " . count($foundPremiumFeatures) . "\n";
}

echo "\n";

echo "2. ⚙️  Checking for premium options:\n";
$foundPremiumOptions = [];
foreach ($premiumOptions as $option) {
    if (preg_match("/" . preg_quote($option, '/') . "/", $configContent)) {
        $foundPremiumOptions[] = $option;
        echo "   ❌ Found premium option: $option\n";
    }
}

if (empty($foundPremiumOptions)) {
    echo "   ✅ No premium options found\n";
} else {
    echo "   ⚠️  Total premium options found: " . count($foundPremiumOptions) . "\n";
}

echo "\n";

// Check for no-api-key usage
echo "3. 🔗 Checking CDN configuration:\n";
$createPath = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createPath)) {
    $createContent = file_get_contents($createPath);
    if (preg_match('/no-api-key/', $createContent)) {
        echo "   ⚠️  Using 'no-api-key' - this will show warnings in TinyMCE 6\n";
        echo "   💡 Consider using a local TinyMCE installation or getting a free API key\n";
    } else {
        echo "   ✅ Not using 'no-api-key'\n";
    }
    
    if (preg_match('/cdn\.tiny\.cloud.*?tinymce\/(\d+)/', $createContent, $matches)) {
        $version = $matches[1];
        echo "   ℹ️  TinyMCE version: $version\n";
    }
} else {
    echo "   ❌ Create blade file not found\n";
}

echo "\n";

// Summary
echo "📊 PREMIUM FEATURES SUMMARY:\n";
echo "============================\n";

$totalIssues = count($foundPremiumFeatures) + count($foundPremiumOptions);
if ($totalIssues === 0) {
    echo "✅ NO PREMIUM FEATURES DETECTED - Free version ready!\n";
    echo "✅ Configuration should work without API key warnings\n";
    echo "✅ Only core, free TinyMCE features are being used\n";
} else {
    echo "❌ PREMIUM FEATURES DETECTED: $totalIssues features require API key\n";
    echo "❌ These features will cause warnings or require payment\n";
    echo "🔧 Remove premium features to eliminate warnings\n";
}

echo "\n";

// Recommendations for free version
echo "💡 FREE VERSION RECOMMENDATIONS:\n";
echo "================================\n";
echo "1. Use only core plugins: advlist, autolink, lists, link, image, charmap, preview, anchor, searchreplace, visualblocks, code, fullscreen, insertdatetime, media, table, help, wordcount\n";
echo "2. Remove toolbar features: fontfamily, fontsize, forecolor, backcolor, styleselect\n";
echo "3. Remove premium options: font_family_formats, font_size_formats, file_picker_callback\n";
echo "4. Consider getting a free TinyMCE API key from https://www.tiny.cloud/\n";
echo "5. Or use a local TinyMCE installation instead of CDN\n";

echo "\n✅ Premium features check complete!\n";
?>
