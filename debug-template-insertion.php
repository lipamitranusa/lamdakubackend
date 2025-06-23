<?php
/**
 * Debug Script for Rich Text Editor Template Insertion
 * Test toolbar functionality dan template insertion
 */

echo "🧪 DEBUGGING RICH TEXT EDITOR TEMPLATE INSERTION\n";
echo "================================================\n\n";

$files_to_check = [
    'TinyMCE CDN' => 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js',
    'Advanced Editor JS' => 'public/js/advanced-editor.js',
    'Article Content CSS' => 'public/css/article-content-styling.css',
    'Create Article View' => 'resources/views/admin/articles/create.blade.php',
    'Edit Article View' => 'resources/views/admin/articles/edit.blade.php'
];

echo "📁 FILE CHECKS:\n";
echo "---------------\n";

foreach ($files_to_check as $name => $path) {
    if ($name === 'TinyMCE CDN') {
        echo "✅ $name: Available (external CDN)\n";
        continue;
    }
    
    if (file_exists($path)) {
        $size = filesize($path);
        echo "✅ $name: Found (" . number_format($size) . " bytes)\n";
    } else {
        echo "❌ $name: NOT FOUND\n";
    }
}

echo "\n🔧 JAVASCRIPT FUNCTIONS CHECK:\n";
echo "-------------------------------\n";

// Check for insertTemplate function in create.blade.php
$createFile = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createFile)) {
    $content = file_get_contents($createFile);
    
    // Check for insertTemplate function
    if (strpos($content, 'function insertTemplate(') !== false) {
        echo "✅ insertTemplate function: Found\n";
    } else {
        echo "❌ insertTemplate function: NOT FOUND\n";
    }
    
    // Check for TinyMCE initialization
    if (strpos($content, 'tinymce.init') !== false) {
        echo "✅ TinyMCE initialization: Found\n";
    } else {
        echo "❌ TinyMCE initialization: NOT FOUND\n";
    }
    
    // Check for toolbar buttons
    if (strpos($content, 'onclick="insertTemplate(') !== false) {
        preg_match_all('/onclick="insertTemplate\(\'([^\']+)\'\)"/', $content, $matches);
        echo "✅ Toolbar buttons found: " . count($matches[1]) . " buttons\n";
        echo "   Templates: " . implode(', ', $matches[1]) . "\n";
    } else {
        echo "❌ Toolbar buttons: NOT FOUND\n";
    }
    
    // Check for advanced editor config
    if (strpos($content, 'advancedEditorConfig') !== false) {
        echo "✅ Advanced editor config reference: Found\n";
    } else {
        echo "❌ Advanced editor config reference: NOT FOUND\n";
    }
} else {
    echo "❌ Cannot check create.blade.php - file not found\n";
}

echo "\n⚙️ ADVANCED EDITOR CONFIG CHECK:\n";
echo "---------------------------------\n";

$advancedEditorFile = 'public/js/advanced-editor.js';
if (file_exists($advancedEditorFile)) {
    $content = file_get_contents($advancedEditorFile);
    
    // Check for config object
    if (strpos($content, 'advancedEditorConfig') !== false) {
        echo "✅ advancedEditorConfig object: Found\n";
    } else {
        echo "❌ advancedEditorConfig object: NOT FOUND\n";
    }
    
    // Check for templates
    if (strpos($content, 'templates:') !== false) {
        preg_match_all('/title:\s*[\'"]([^\'\"]+)[\'"]/', $content, $matches);
        echo "✅ TinyMCE templates found: " . count($matches[1]) . " templates\n";
        foreach ($matches[1] as $template) {
            echo "   - $template\n";
        }
    } else {
        echo "❌ TinyMCE templates: NOT FOUND\n";
    }
    
    // Check for custom toolbar
    if (strpos($content, 'customTemplates') !== false) {
        echo "✅ Custom templates toolbar: Found\n";
    } else {
        echo "❌ Custom templates toolbar: NOT FOUND\n";
    }
} else {
    echo "❌ Cannot check advanced-editor.js - file not found\n";
}

echo "\n🎨 CSS STYLING CHECK:\n";
echo "---------------------\n";

$cssFile = 'public/css/article-content-styling.css';
if (file_exists($cssFile)) {
    $content = file_get_contents($cssFile);
    
    // Check for key CSS classes
    $cssClasses = [
        '.article-content' => 'Article content wrapper',
        '.callout' => 'Callout boxes',
        '.callout.info' => 'Info callout styling',
        '.callout.warning' => 'Warning callout styling',
        'blockquote' => 'Quote styling',
        'pre code' => 'Code block styling'
    ];
    
    foreach ($cssClasses as $class => $description) {
        if (strpos($content, $class) !== false) {
            echo "✅ $description ($class): Found\n";
        } else {
            echo "❌ $description ($class): NOT FOUND\n";
        }
    }
} else {
    echo "❌ Cannot check article-content-styling.css - file not found\n";
}

echo "\n🌐 BROWSER TESTING RECOMMENDATIONS:\n";
echo "-----------------------------------\n";
echo "1. Open browser developer tools (F12)\n";
echo "2. Go to Console tab\n";
echo "3. Navigate to article create page\n";
echo "4. Look for these console messages:\n";
echo "   ✅ '🚀 Initializing Rich Text Editor...'\n";
echo "   ✅ '✅ Advanced editor config found, initializing...'\n";
echo "   ✅ '✅ TinyMCE initialized successfully'\n";
echo "5. Click toolbar buttons and check for:\n";
echo "   ✅ '🎯 Inserting template: [template-name]'\n";
echo "   ✅ '✅ Using TinyMCE editor' or '⚠️ TinyMCE not available, using fallback'\n";
echo "   ✅ '✅ Template inserted successfully'\n\n";

echo "🔍 TROUBLESHOOTING STEPS:\n";
echo "-------------------------\n";
echo "If toolbar buttons don't work:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Verify TinyMCE loads without errors\n";
echo "3. Ensure insertTemplate function is defined\n";
echo "4. Check if buttons have correct onclick handlers\n";
echo "5. Verify TinyMCE is fully initialized before clicking\n\n";

echo "📝 TEST PROCEDURE:\n";
echo "------------------\n";
echo "1. Navigate to /admin/articles/create\n";
echo "2. Wait for page to fully load\n";
echo "3. Check that toolbar buttons are visible\n";
echo "4. Click each template button one by one\n";
echo "5. Verify templates are inserted into editor\n";
echo "6. Check if templates have proper HTML formatting\n";
echo "7. Save article and verify frontend display\n\n";

echo "✅ DEBUG SCRIPT COMPLETED\n";
echo "========================\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
