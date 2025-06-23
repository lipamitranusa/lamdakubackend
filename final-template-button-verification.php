<?php
/**
 * Final Template Button Fix Verification Script
 * Verifies that all template button fixes have been properly implemented
 */

echo "=== FINAL TEMPLATE BUTTON FIX VERIFICATION ===\n\n";

// Check create.blade.php file
$createFile = 'resources/views/admin/articles/create.blade.php';
if (!file_exists($createFile)) {
    echo "❌ ERROR: create.blade.php not found!\n";
    exit(1);
}

$content = file_get_contents($createFile);
echo "✅ create.blade.php loaded (" . number_format(strlen($content)) . " characters)\n\n";

// Verification checks
$checks = [
    'Basic Template Buttons' => [
        'patterns' => [
            'onclick="insertTemplate(\'article-intro\')"',
            'onclick="insertTemplate(\'heading-section\')"',
            'onclick="insertTemplate(\'bullet-points\')"',
            'onclick="insertTemplate(\'step-by-step\')"',
            'onclick="insertTemplate(\'callout-info\')"',
            'onclick="insertTemplate(\'callout-warning\')"',
            'onclick="insertTemplate(\'quote\')"',
            'onclick="insertTemplate(\'code-example\')"'
        ],
        'description' => 'All 8 template buttons with onclick handlers'
    ],
    
    'Core JavaScript Functions' => [
        'patterns' => [
            'function insertTemplate(type)',
            'function insertTinyMCETemplate(editor, type)',
            'function insertPlainTemplate(type)',
            'function testTemplateInsertion()',
            'function debugEditorState()'
        ],
        'description' => 'Core template insertion functions'
    ],
    
    'Template Content Definitions' => [
        'patterns' => [
            'case \'article-intro\':',
            'case \'heading-section\':',
            'case \'bullet-points\':',
            'case \'step-by-step\':',
            'case \'callout-info\':',
            'case \'callout-warning\':',
            'case \'quote\':',
            'case \'code-example\':'
        ],
        'description' => 'Template case definitions in switch statements'
    ],
    
    'TinyMCE Integration' => [
        'patterns' => [
            'tinymce.init(',
            'advancedEditorConfig',
            'window.tinymceEditor',
            'editor.insertContent',
            'editor.focus()'
        ],
        'description' => 'TinyMCE editor integration components'
    ],
    
    'Enhanced Event Listeners' => [
        'patterns' => [
            'function setupTemplateButtons()',
            'removeAttribute(\'onclick\')',
            'addEventListener(\'click\'',
            'Event delegation for all buttons',
            'template-buttons-ready'
        ],
        'description' => 'Enhanced event handling and fallback mechanisms'
    ],
    
    'Debug and Error Handling' => [
        'patterns' => [
            'console.log(',
            'console.error(',
            'try {',
            'catch (error)',
            'showAutoSaveIndicator'
        ],
        'description' => 'Debug logging and error handling'
    ],
    
    'Fallback Mechanisms' => [
        'patterns' => [
            'insertPlainTemplate(type)',
            'document.getElementById(\'content\')',
            'textarea.value',
            'selectionStart',
            'setSelectionRange'
        ],
        'description' => 'Fallback textarea insertion methods'
    ]
];

$overallStatus = true;

foreach ($checks as $checkName => $checkData) {
    echo "🔍 CHECKING: $checkName\n";
    echo str_repeat('-', 50) . "\n";
    
    $foundCount = 0;
    $totalCount = count($checkData['patterns']);
    
    foreach ($checkData['patterns'] as $pattern) {
        $found = strpos($content, $pattern) !== false;
        $status = $found ? '✅' : '❌';
        echo sprintf("   %-40s: %s\n", substr($pattern, 0, 40), $status);
        
        if ($found) {
            $foundCount++;
        }
    }
    
    $percentage = round(($foundCount / $totalCount) * 100, 1);
    $checkStatus = $foundCount === $totalCount;
    
    if (!$checkStatus) {
        $overallStatus = false;
    }
    
    echo sprintf("\n   RESULT: %d/%d patterns found (%.1f%%) %s\n", 
        $foundCount, $totalCount, $percentage, $checkStatus ? '✅ PASS' : '❌ FAIL');
    echo "   " . $checkData['description'] . "\n\n";
}

// Check for syntax issues
echo "🔍 CHECKING: JavaScript Syntax\n";
echo str_repeat('-', 50) . "\n";

// Count brackets and parentheses
$openBraces = substr_count($content, '{');
$closeBraces = substr_count($content, '}');
$openParens = substr_count($content, '(');
$closeParens = substr_count($content, ')');

$braceMatch = $openBraces === $closeBraces;
$parenMatch = $openParens === $closeParens;

echo sprintf("   Curly braces: %d open, %d close %s\n", $openBraces, $closeBraces, $braceMatch ? '✅' : '❌');
echo sprintf("   Parentheses:  %d open, %d close %s\n", $openParens, $closeParens, $parenMatch ? '✅' : '❌');

if (!$braceMatch || !$parenMatch) {
    $overallStatus = false;
}

// Check for duplicate functions
echo "\n🔍 CHECKING: Function Duplicates\n";
echo str_repeat('-', 50) . "\n";

$functions = ['insertTemplate', 'insertTinyMCETemplate', 'insertPlainTemplate', 'testTemplateInsertion', 'debugEditorState'];
foreach ($functions as $func) {
    $count = substr_count($content, "function $func(");
    $status = $count === 1 ? '✅' : ($count > 1 ? '⚠️ DUPLICATE' : '❌ MISSING');
    echo sprintf("   %-25s: %d occurrences %s\n", $func, $count, $status);
    
    if ($count !== 1) {
        $overallStatus = false;
    }
}

// Final summary
echo "\n" . str_repeat('=', 60) . "\n";
echo "FINAL VERIFICATION RESULT\n";
echo str_repeat('=', 60) . "\n";

if ($overallStatus) {
    echo "✅ ALL CHECKS PASSED!\n";
    echo "✅ Template buttons should work correctly now.\n\n";
    echo "NEXT STEPS:\n";
    echo "1. Open browser and navigate to /admin/articles/create\n";
    echo "2. Open browser console (F12)\n";
    echo "3. Click template buttons and verify they work\n";
    echo "4. Check console for success messages\n";
    echo "5. If still not working, use the debug guide: TEMPLATE_BUTTON_DEBUG_GUIDE.md\n";
} else {
    echo "❌ SOME CHECKS FAILED!\n";
    echo "❌ There may be issues with the template button implementation.\n\n";
    echo "RECOMMENDED ACTIONS:\n";
    echo "1. Review failed checks above\n";
    echo "2. Fix any missing or duplicate functions\n";
    echo "3. Check JavaScript syntax carefully\n";
    echo "4. Re-run this verification script\n";
}

echo "\nADDITIONAL DEBUG RESOURCES:\n";
echo "- Standalone test page: /public/template-button-debug.html\n";
echo "- Debug guide: TEMPLATE_BUTTON_DEBUG_GUIDE.md\n";
echo "- Template button test script: test-template-buttons.php\n";

echo "\n" . str_repeat('=', 60) . "\n";
echo "Template Button Fix Verification Complete!\n";
echo str_repeat('=', 60) . "\n";
?>
