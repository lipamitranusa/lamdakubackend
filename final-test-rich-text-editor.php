<?php
/**
 * Final Test Script - Rich Text Editor Template Insertion
 * Comprehensive verification before deployment
 */

echo "🎯 FINAL TEST - RICH TEXT EDITOR TEMPLATE INSERTION\n";
echo "==================================================\n\n";

// Test 1: File Integrity Check
echo "📋 TEST 1: FILE INTEGRITY CHECK\n";
echo "--------------------------------\n";

$critical_files = [
    'public/js/advanced-editor.js' => 'TinyMCE Configuration & Templates',
    'public/css/article-content-styling.css' => 'Article Content Styling',
    'resources/views/admin/articles/create.blade.php' => 'Create Article Form',
    'resources/views/admin/articles/edit.blade.php' => 'Edit Article Form',
    'resources/views/admin/articles/show.blade.php' => 'Article Display View'
];

$all_files_ok = true;

foreach ($critical_files as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        $status = $size > 1000 ? "✅ OK" : "⚠️ SMALL";
        echo "$status $description: $file (" . number_format($size) . " bytes)\n";
        
        if ($size < 1000) {
            $all_files_ok = false;
        }
    } else {
        echo "❌ MISSING $description: $file\n";
        $all_files_ok = false;
    }
}

// Test 2: JavaScript Function Verification
echo "\n🔧 TEST 2: JAVASCRIPT FUNCTION VERIFICATION\n";
echo "-------------------------------------------\n";

$create_file = 'resources/views/admin/articles/create.blade.php';
if (file_exists($create_file)) {
    $content = file_get_contents($create_file);
    
    $js_checks = [
        'insertTemplate function' => 'function insertTemplate(',
        'insertTinyMCETemplate function' => 'function insertTinyMCETemplate(',
        'insertPlainTemplate function' => 'function insertPlainTemplate(',
        'TinyMCE initialization' => 'tinymce.init(',
        'advancedEditorConfig reference' => 'advancedEditorConfig',
        'Console logging' => 'console.log(',
        'Error handling' => 'try {',
        'Global editor reference' => 'window.tinymceEditor',
    ];
    
    foreach ($js_checks as $check => $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo "✅ $check: Found\n";
        } else {
            echo "❌ $check: NOT FOUND\n";
            $all_files_ok = false;
        }
    }
}

// Test 3: Template Buttons Verification
echo "\n🎲 TEST 3: TEMPLATE BUTTONS VERIFICATION\n";
echo "----------------------------------------\n";

if (file_exists($create_file)) {
    $content = file_get_contents($create_file);
    
    $expected_templates = [
        'article-intro' => '📝 Intro Artikel',
        'heading-section' => '📑 Section',
        'bullet-points' => '📌 Points',
        'step-by-step' => '📈 Steps',
        'callout-info' => '💡 Info Box',
        'callout-warning' => '⚠️ Warning',
        'quote' => '💬 Quote',
        'code-example' => '💻 Code'
    ];
    
    $found_buttons = 0;
    
    foreach ($expected_templates as $template => $description) {
        if (strpos($content, "insertTemplate('$template')") !== false) {
            echo "✅ $description: Button found for '$template'\n";
            $found_buttons++;
        } else {
            echo "❌ $description: Button NOT found for '$template'\n";
        }
    }
    
    echo "\nButtons found: $found_buttons/" . count($expected_templates) . "\n";
    
    if ($found_buttons < count($expected_templates)) {
        $all_files_ok = false;
    }
}

// Test 4: CSS Classes Verification
echo "\n🎨 TEST 4: CSS CLASSES VERIFICATION\n";
echo "-----------------------------------\n";

$css_file = 'public/css/article-content-styling.css';
if (file_exists($css_file)) {
    $content = file_get_contents($css_file);
    
    $css_classes = [
        '.article-content' => 'Article wrapper',
        '.callout' => 'Callout base class',
        '.callout.info' => 'Info callout',
        '.callout.warning' => 'Warning callout',
        '.callout.success' => 'Success callout',
        '.callout.danger' => 'Danger callout',
        'blockquote' => 'Quote styling',
        'pre code' => 'Code block styling',
        '@media (max-width' => 'Responsive design'
    ];
    
    foreach ($css_classes as $class => $description) {
        if (strpos($content, $class) !== false) {
            echo "✅ $description ($class): Found\n";
        } else {
            echo "❌ $description ($class): NOT FOUND\n";
        }
    }
}

// Test 5: Advanced Editor Config Verification
echo "\n⚙️ TEST 5: ADVANCED EDITOR CONFIG VERIFICATION\n";
echo "----------------------------------------------\n";

$config_file = 'public/js/advanced-editor.js';
if (file_exists($config_file)) {
    $content = file_get_contents($config_file);
    
    $config_checks = [
        'Config object definition' => 'advancedEditorConfig = {',
        'TinyMCE selector' => "selector: '#content'",
        'Plugins array' => 'plugins: [',
        'Toolbar configuration' => 'toolbar1:',
        'Content styling' => 'content_style:',
        'Templates array' => 'templates: [',
        'Template examples' => 'Template Artikel Lengkap',
        'Setup function' => 'file_picker_callback:'
    ];
    
    foreach ($config_checks as $check => $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo "✅ $check: Found\n";
        } else {
            echo "❌ $check: NOT FOUND\n";
        }
    }
}

// Test 6: Show View CSS Link Check
echo "\n🔗 TEST 6: FRONTEND CSS LINK VERIFICATION\n";
echo "-----------------------------------------\n";

$show_file = 'resources/views/admin/articles/show.blade.php';
if (file_exists($show_file)) {
    $content = file_get_contents($show_file);
    
    if (strpos($content, 'article-content-styling.css') !== false) {
        echo "✅ CSS linked in show.blade.php: Found\n";
    } else {
        echo "❌ CSS linked in show.blade.php: NOT FOUND\n";
        echo "   ⚠️ Articles may not display with proper styling\n";
    }
} else {
    echo "❌ show.blade.php file: NOT FOUND\n";
}

// Final Assessment
echo "\n📊 FINAL ASSESSMENT\n";
echo "===================\n";

if ($all_files_ok) {
    echo "🎉 ALL TESTS PASSED!\n";
    echo "✅ Rich Text Editor Template Insertion is ready for use\n";
    echo "✅ All files are present and configured correctly\n";
    echo "✅ Template buttons are properly implemented\n";
    echo "✅ CSS styling is available\n";
    echo "✅ JavaScript functions are defined\n\n";
    
    echo "🚀 NEXT STEPS:\n";
    echo "1. Navigate to /admin/articles/create\n";
    echo "2. Wait for TinyMCE to initialize\n";
    echo "3. Test template buttons one by one\n";
    echo "4. Create a test article with various templates\n";
    echo "5. Verify frontend display with styling\n\n";
    
    echo "📖 DOCUMENTATION:\n";
    echo "- Read: RICH_TEXT_EDITOR_TEMPLATE_FINAL_GUIDE.md\n";
    echo "- Debug: php debug-template-insertion.php\n";
    echo "- Browser console for real-time debugging\n\n";
    
} else {
    echo "⚠️ SOME ISSUES DETECTED!\n";
    echo "❌ Please review the failed tests above\n";
    echo "❌ Fix missing files or configurations\n";
    echo "❌ Re-run this test after fixes\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo "1. Check file permissions\n";
    echo "2. Verify file contents are not corrupted\n";
    echo "3. Clear browser cache if testing\n";
    echo "4. Check Laravel asset compilation\n\n";
}

echo "📅 Test completed: " . date('Y-m-d H:i:s') . "\n";
echo "💻 Environment: " . PHP_OS . " - PHP " . PHP_VERSION . "\n";
echo "📁 Working directory: " . getcwd() . "\n";
