<?php
/**
 * Script untuk test Template Button Functionality
 * Tests the template insertion buttons on article creation page
 */

echo "=== TEMPLATE BUTTON FUNCTIONALITY TEST ===\n\n";

// Check if required files exist
$files_to_check = [
    'resources/views/admin/articles/create.blade.php',
    'public/js/advanced-editor.js',
    'public/css/article-content-styling.css'
];

echo "1. CHECKING REQUIRED FILES:\n";
echo "============================\n";
foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    echo sprintf("%-45s: %s\n", $file, $exists ? '✅ EXISTS' : '❌ MISSING');
    
    if (!$exists) {
        echo "   ERROR: Required file is missing!\n";
        continue;
    }
    
    // Check file size and modification date
    $size = filesize($file);
    $modified = date('Y-m-d H:i:s', filemtime($file));
    echo sprintf("   Size: %s bytes, Modified: %s\n", number_format($size), $modified);
}

echo "\n";

// Check create.blade.php for template buttons
echo "2. CHECKING TEMPLATE BUTTONS IN CREATE.BLADE.PHP:\n";
echo "==================================================\n";

$createFile = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createFile)) {
    $content = file_get_contents($createFile);
    
    // Check for template buttons
    $buttons = [
        'article-intro' => 'Intro Artikel',
        'heading-section' => 'Section',
        'bullet-points' => 'Points',
        'step-by-step' => 'Steps',
        'callout-info' => 'Info Box',
        'callout-warning' => 'Warning',
        'quote' => 'Quote',
        'code-example' => 'Code'
    ];
    
    foreach ($buttons as $templateType => $buttonText) {
        $buttonPattern = 'onclick="insertTemplate(\'' . $templateType . '\')"';
        $exists = strpos($content, $buttonPattern) !== false;
        echo sprintf("%-20s (%s): %s\n", $templateType, $buttonText, $exists ? '✅ FOUND' : '❌ MISSING');
    }
    
    // Check for JavaScript functions
    echo "\n3. CHECKING JAVASCRIPT FUNCTIONS:\n";
    echo "==================================\n";
    
    $functions = [
        'insertTemplate' => 'Main template insertion function',
        'insertTinyMCETemplate' => 'TinyMCE template insertion',
        'insertPlainTemplate' => 'Fallback template insertion',
        'testTemplateInsertion' => 'Debug test function',
        'debugEditorState' => 'Debug state function'
    ];
    
    foreach ($functions as $funcName => $description) {
        $functionPattern = 'function ' . $funcName;
        $exists = strpos($content, $functionPattern) !== false;
        echo sprintf("%-25s: %s\n", $funcName, $exists ? '✅ FOUND' : '❌ MISSING');
        echo sprintf("%-25s  %s\n", '', $description);
    }
    
    // Check for TinyMCE configuration
    echo "\n4. CHECKING TINYMCE INTEGRATION:\n";
    echo "=================================\n";
    
    $tinyMCEChecks = [
        'TinyMCE CDN' => 'cdn.tiny.cloud',
        'Advanced Editor Config' => 'advanced-editor.js',
        'TinyMCE Init' => 'tinymce.init',
        'Editor Setup' => 'setup: function',
        'Global Editor' => 'window.tinymceEditor'
    ];
    
    foreach ($tinyMCEChecks as $checkName => $pattern) {
        $exists = strpos($content, $pattern) !== false;
        echo sprintf("%-25s: %s\n", $checkName, $exists ? '✅ FOUND' : '❌ MISSING');
    }
    
    // Check for backup event listeners
    echo "\n5. CHECKING BACKUP EVENT LISTENERS:\n";
    echo "=====================================\n";
    
    $hasBackupListeners = strpos($content, 'addEventListener') !== false;
    $hasOnclickBackup = strpos($content, 'insertTemplate(templateType)') !== false;
    
    echo sprintf("%-25s: %s\n", 'Backup Event Listeners', $hasBackupListeners ? '✅ FOUND' : '❌ MISSING');
    echo sprintf("%-25s: %s\n", 'OnClick Backup', $hasOnclickBackup ? '✅ FOUND' : '❌ MISSING');
    
    // Check for syntax issues
    echo "\n6. CHECKING FOR POTENTIAL SYNTAX ISSUES:\n";
    echo "==========================================\n";
    
    // Count brackets
    $openBraces = substr_count($content, '{');
    $closeBraces = substr_count($content, '}');
    $openParens = substr_count($content, '(');
    $closeParens = substr_count($content, ')');
    
    echo sprintf("%-25s: %d open, %d close %s\n", 'Curly Braces', $openBraces, $closeBraces, 
        ($openBraces === $closeBraces) ? '✅' : '❌ UNMATCHED');
    echo sprintf("%-25s: %d open, %d close %s\n", 'Parentheses', $openParens, $closeParens, 
        ($openParens === $closeParens) ? '✅' : '❌ UNMATCHED');
    
    // Check for common issues
    $commonIssues = [
        'Double });' => '});.*?});',
        'Missing semicolon' => 'function\s+\w+.*?}\s*$',
        'Unclosed strings' => '["\'](?:[^"\'\\\\]|\\\\.)*$'
    ];
    
    foreach ($commonIssues as $issueName => $pattern) {
        $matches = preg_match('/' . $pattern . '/m', $content);
        echo sprintf("%-25s: %s\n", $issueName, $matches ? '⚠️ POSSIBLE ISSUE' : '✅ OK');
    }
}

// Check advanced-editor.js
echo "\n7. CHECKING ADVANCED-EDITOR.JS:\n";
echo "================================\n";

$jsFile = 'public/js/advanced-editor.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    
    $jsChecks = [
        'advancedEditorConfig' => 'Configuration object exists',
        'templates:' => 'Templates array exists',
        'setup: function' => 'Setup function exists',
        'insertContent' => 'Insert content method exists'
    ];
    
    foreach ($jsChecks as $pattern => $description) {
        $exists = strpos($jsContent, $pattern) !== false;
        echo sprintf("%-25s: %s\n", $pattern, $exists ? '✅ FOUND' : '❌ MISSING');
        echo sprintf("%-25s  %s\n", '', $description);
    }
} else {
    echo "❌ advanced-editor.js file not found!\n";
}

echo "\n8. RECOMMENDATIONS:\n";
echo "====================\n";
echo "To test template buttons:\n";
echo "1. Open browser and navigate to /admin/articles/create\n";
echo "2. Open browser console (F12)\n";
echo "3. Click on template buttons and check console logs\n";
echo "4. Use 'Test Template' and 'Debug Info' buttons for troubleshooting\n";
echo "5. If buttons don't work, check browser console for JavaScript errors\n";

echo "\n✅ Template Button Test Completed!\n";
echo "Check the results above for any issues.\n";
echo "If all checks pass but buttons still don't work, test in browser console.\n";
?>
