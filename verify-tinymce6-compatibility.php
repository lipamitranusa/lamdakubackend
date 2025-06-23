<?php
/**
 * TinyMCE 6 Compatibility Verification Script
 * Checks for deprecated and removed features in the advanced editor configuration
 */

echo "🔍 TinyMCE 6 Compatibility Verification\n";
echo "=====================================\n\n";

// Read the advanced editor configuration
$configPath = 'public/js/advanced-editor.js';
if (!file_exists($configPath)) {
    echo "❌ Advanced editor config file not found at: $configPath\n";
    exit(1);
}

$configContent = file_get_contents($configPath);

// List of deprecated/removed plugins in TinyMCE 6
$deprecatedPlugins = [
    'colorpicker',
    'textcolor',
    'template',
    'hr',
    'pagebreak',
    'toc',
    'imagetools',
    'paste',
    'nonbreaking',
    'emoticons',  // May cause issues
    'codesample', // May cause issues
    'directionality', // May cause issues
    'visualchars', // May cause issues
    'quickbars' // May cause issues
];

// List of deprecated/removed options in TinyMCE 6
$deprecatedOptions = [
    'table_responsive_width',
    'templates',
    'paste_auto_cleanup_on_paste',
    'paste_remove_styles',
    'paste_remove_styles_if_webkit',
    'link_context_toolbar',
    'table_default_attributes',
    'quickbars_selection_toolbar',
    'quickbars_insert_toolbar',
    'a11y_advanced_options'
];

// Check for deprecated plugins
echo "1. 🔌 Checking for deprecated plugins:\n";
$foundDeprecatedPlugins = [];
foreach ($deprecatedPlugins as $plugin) {
    if (preg_match("/['\"]" . $plugin . "['\"]/", $configContent)) {
        $foundDeprecatedPlugins[] = $plugin;
        echo "   ❌ Found deprecated plugin: $plugin\n";
    }
}

if (empty($foundDeprecatedPlugins)) {
    echo "   ✅ No deprecated plugins found\n";
} else {
    echo "   ⚠️  Total deprecated plugins found: " . count($foundDeprecatedPlugins) . "\n";
}

echo "\n";

// Check for deprecated options
echo "2. ⚙️  Checking for deprecated options:\n";
$foundDeprecatedOptions = [];
foreach ($deprecatedOptions as $option) {
    if (preg_match("/$option\s*:/", $configContent)) {
        $foundDeprecatedOptions[] = $option;
        echo "   ❌ Found deprecated option: $option\n";
    }
}

if (empty($foundDeprecatedOptions)) {
    echo "   ✅ No deprecated options found\n";
} else {
    echo "   ⚠️  Total deprecated options found: " . count($foundDeprecatedOptions) . "\n";
}

echo "\n";

// Check for safe plugins list
echo "3. 📦 Checking current plugins configuration:\n";
if (preg_match('/plugins:\s*\[(.*?)\]/s', $configContent, $matches)) {
    $pluginsString = $matches[1];
    $plugins = array_map('trim', explode(',', str_replace(['"', "'", "\n", "\r"], '', $pluginsString)));
    $plugins = array_filter($plugins); // Remove empty items
    
    echo "   📋 Current plugins (" . count($plugins) . "):\n";
    foreach ($plugins as $plugin) {
        $plugin = trim($plugin);
        if (!empty($plugin)) {
            $isDeprecated = in_array($plugin, $deprecatedPlugins);
            echo "      " . ($isDeprecated ? "❌" : "✅") . " $plugin" . ($isDeprecated ? " (DEPRECATED)" : "") . "\n";
        }
    }
} else {
    echo "   ❌ Could not parse plugins configuration\n";
}

echo "\n";

// Check TinyMCE version in create.blade.php
echo "4. 🔗 Checking TinyMCE CDN version:\n";
$createPath = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createPath)) {
    $createContent = file_get_contents($createPath);
    if (preg_match('/cdn\.tiny\.cloud.*?tinymce\/(\d+)/', $createContent, $matches)) {
        $version = $matches[1];
        echo "   ✅ TinyMCE version: $version\n";
        if ($version >= 6) {
            echo "   ✅ Version is compatible (6+)\n";
        } else {
            echo "   ❌ Version is too old (needs 6+)\n";
        }
    } else {
        echo "   ⚠️  Could not detect TinyMCE version from CDN URL\n";
    }
} else {
    echo "   ❌ Create blade file not found\n";
}

echo "\n";

// Summary
echo "📊 COMPATIBILITY SUMMARY:\n";
echo "========================\n";

$issues = count($foundDeprecatedPlugins) + count($foundDeprecatedOptions);
if ($issues === 0) {
    echo "✅ ALL CHECKS PASSED - TinyMCE 6 compatibility verified!\n";
    echo "✅ No deprecated plugins or options found\n";
    echo "✅ Configuration should work without errors\n";
} else {
    echo "❌ ISSUES FOUND: $issues compatibility problems detected\n";
    echo "❌ Configuration may cause console errors or plugin failures\n";
    echo "🔧 Please remove deprecated features listed above\n";
}

echo "\n";

// Recommendations
echo "💡 RECOMMENDATIONS:\n";
echo "==================\n";
echo "1. Use only core TinyMCE 6 plugins: advlist, autolink, lists, link, image, charmap, preview, anchor, searchreplace, visualblocks, code, fullscreen, insertdatetime, media, table, help, wordcount\n";
echo "2. Avoid premium or deprecated plugins like colorpicker, textcolor, template, hr, pagebreak, toc, imagetools\n";
echo "3. Use simplified configuration options compatible with TinyMCE 6\n";
echo "4. Test in browser console for any remaining 404 errors or deprecation warnings\n";

echo "\n✅ Verification complete!\n";
?>
