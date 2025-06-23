<?php
/**
 * FINAL VERIFICATION - Rich Text Editor Ready Check
 * Comprehensive test before user testing
 */

echo "🎯 FINAL VERIFICATION - RICH TEXT EDITOR\n";
echo "==========================================\n\n";

$errors = [];
$warnings = [];

// Test 1: Critical Files
echo "📁 CRITICAL FILES CHECK:\n";
echo "------------------------\n";

$files = [
    'public/js/advanced-editor.js' => 'TinyMCE Config',
    'public/css/article-content-styling.css' => 'Article CSS',
    'resources/views/admin/articles/create.blade.php' => 'Create Form',
    'resources/views/admin/articles/edit.blade.php' => 'Edit Form',
    'resources/views/admin/articles/show.blade.php' => 'Display View'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✅ $desc: " . number_format($size) . " bytes\n";
    } else {
        echo "❌ $desc: MISSING\n";
        $errors[] = "Missing file: $file";
    }
}

// Test 2: Blade Syntax Verification
echo "\n🔍 BLADE SYNTAX VERIFICATION:\n";
echo "-----------------------------\n";

$createFile = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createFile)) {
    $content = file_get_contents($createFile);
    
    // Check push/endpush
    $pushCount = substr_count($content, '@push');
    $endpushCount = substr_count($content, '@endpush');
    
    if ($pushCount === $endpushCount) {
        echo "✅ Push/Endpush balance: $pushCount/$endpushCount\n";
    } else {
        echo "❌ Push/Endpush imbalance: $pushCount/$endpushCount\n";
        $errors[] = "Blade push/endpush imbalance";
    }
    
    // Check for duplicates
    if (strpos($content, '@endpush@endpush') === false && 
        strpos($content, '</script></script>') === false) {
        echo "✅ No duplicate closing tags\n";
    } else {
        echo "❌ Duplicate closing tags found\n";
        $errors[] = "Duplicate closing tags";
    }
    
    // Check JavaScript functions
    $functions = [
        'insertTemplate(' => 'Template insertion function',
        'insertTinyMCETemplate(' => 'TinyMCE template function',
        'insertPlainTemplate(' => 'Fallback template function',
        'tinymce.init(' => 'TinyMCE initialization',
        'advancedEditorConfig' => 'Advanced config reference'
    ];
    
    foreach ($functions as $func => $desc) {
        if (strpos($content, $func) !== false) {
            echo "✅ $desc: Found\n";
        } else {
            echo "❌ $desc: NOT FOUND\n";
            $errors[] = "Missing: $desc";
        }
    }
}

// Test 3: Template Buttons
echo "\n🎲 TEMPLATE BUTTONS CHECK:\n";
echo "--------------------------\n";

$templates = [
    'article-intro' => '📝 Intro',
    'heading-section' => '📑 Section', 
    'bullet-points' => '📌 Points',
    'step-by-step' => '📈 Steps',
    'callout-info' => '💡 Info',
    'callout-warning' => '⚠️ Warning',
    'quote' => '💬 Quote',
    'code-example' => '💻 Code'
];

foreach ($templates as $template => $label) {
    if (strpos($content, "insertTemplate('$template')") !== false) {
        echo "✅ $label: Button configured\n";
    } else {
        echo "❌ $label: Button missing\n";
        $errors[] = "Missing template button: $template";
    }
}

// Test 4: CSS Classes
echo "\n🎨 CSS CLASSES CHECK:\n";
echo "---------------------\n";

$cssFile = 'public/css/article-content-styling.css';
if (file_exists($cssFile)) {
    $cssContent = file_get_contents($cssFile);
    
    $cssClasses = [
        '.callout' => 'Callout base',
        '.callout.info' => 'Info callout',
        '.callout.warning' => 'Warning callout',
        '.article-content' => 'Article wrapper',
        'blockquote' => 'Quote styling',
        'pre code' => 'Code styling'
    ];
    
    foreach ($cssClasses as $class => $desc) {
        if (strpos($cssContent, $class) !== false) {
            echo "✅ $desc: Available\n";
        } else {
            echo "⚠️ $desc: Missing\n";
            $warnings[] = "CSS class missing: $class";
        }
    }
}

// Test 5: Editor Config
echo "\n⚙️ EDITOR CONFIG CHECK:\n";
echo "-----------------------\n";

$editorFile = 'public/js/advanced-editor.js';
if (file_exists($editorFile)) {
    $editorContent = file_get_contents($editorFile);
    
    $configItems = [
        'advancedEditorConfig =' => 'Config object',
        "selector: '#content'" => 'Selector config',
        'templates: [' => 'Templates array',
        'toolbar1:' => 'Toolbar config',
        'content_style:' => 'Content styling'
    ];
    
    foreach ($configItems as $item => $desc) {
        if (strpos($editorContent, $item) !== false) {
            echo "✅ $desc: Configured\n";
        } else {
            echo "❌ $desc: Missing\n";
            $errors[] = "Editor config missing: $desc";
        }
    }
}

// Final Assessment
echo "\n📊 FINAL ASSESSMENT:\n";
echo "====================\n";

if (empty($errors)) {
    echo "🎉 ALL CRITICAL TESTS PASSED!\n";
    echo "✅ Rich Text Editor is ready for use\n";
    echo "✅ Template insertion should work correctly\n";
    echo "✅ No Blade syntax errors detected\n";
    echo "✅ All required files are present\n\n";
    
    if (!empty($warnings)) {
        echo "⚠️ MINOR WARNINGS:\n";
        foreach ($warnings as $warning) {
            echo "   - $warning\n";
        }
        echo "\n";
    }
    
    echo "🚀 USER TESTING STEPS:\n";
    echo "1. Open browser and navigate to /admin/articles/create\n";
    echo "2. Open Developer Tools (F12) and check Console\n";
    echo "3. Wait for TinyMCE to initialize\n";
    echo "4. Look for success messages:\n";
    echo "   - '🚀 Initializing Rich Text Editor...'\n";
    echo "   - '✅ TinyMCE initialized successfully'\n";
    echo "   - 'Editor ready - templates available!'\n";
    echo "5. Click each template button and verify insertion\n";
    echo "6. Create test article with multiple templates\n";
    echo "7. Save and verify frontend display\n\n";
    
    echo "💡 If buttons don't work:\n";
    echo "- Check browser console for errors\n";
    echo "- Verify TinyMCE loads completely\n";
    echo "- Try hard refresh (Ctrl+F5)\n";
    echo "- Check network tab for failed requests\n\n";
    
} else {
    echo "❌ CRITICAL ERRORS DETECTED:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
    echo "🔧 Please fix these errors before testing\n";
    echo "📞 Re-run this script after fixes\n\n";
}

echo "📅 Verification completed: " . date('Y-m-d H:i:s') . "\n";
echo "🌟 Rich Text Editor Implementation Status: " . (empty($errors) ? "READY" : "NEEDS FIXES") . "\n";
