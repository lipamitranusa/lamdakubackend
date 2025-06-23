<?php
/**
 * Verify Edit Page Consistency with Create Page
 * Ensures both create and edit pages have the same TinyMCE configuration
 */

echo "🔄 Edit Page Consistency Verification\n";
echo "====================================\n\n";

$allMatched = true;

// Compare files
$createPath = 'resources/views/admin/articles/create.blade.php';
$editPath = 'resources/views/admin/articles/edit.blade.php';

if (!file_exists($createPath)) {
    echo "❌ Create page not found: $createPath\n";
    exit(1);
}

if (!file_exists($editPath)) {
    echo "❌ Edit page not found: $editPath\n";
    exit(1);
}

$createContent = file_get_contents($createPath);
$editContent = file_get_contents($editPath);

echo "1. 📡 CDN Configuration Check:\n";

// Check CDN sources
if (preg_match('/cdn\.jsdelivr\.net.*?tinymce/', $createContent)) {
    echo "   ✅ Create page: Using jsdelivr CDN\n";
} else {
    echo "   ❌ Create page: Not using jsdelivr CDN\n";
    $allMatched = false;
}

if (preg_match('/cdn\.jsdelivr\.net.*?tinymce/', $editContent)) {
    echo "   ✅ Edit page: Using jsdelivr CDN\n";
} else {
    echo "   ❌ Edit page: Not using jsdelivr CDN\n";
    $allMatched = false;
}

echo "\n2. 🔧 Template Buttons Check:\n";

// Check template buttons
$templateButtons = [
    'insertTemplate(\'article-intro\')',
    'insertTemplate(\'heading-section\')',
    'insertTemplate(\'bullet-points\')',
    'insertTemplate(\'step-by-step\')',
    'insertTemplate(\'callout-info\')',
    'insertTemplate(\'callout-warning\')',
    'insertTemplate(\'quote\')',
    'insertTemplate(\'code-example\')'
];

$createButtonCount = 0;
$editButtonCount = 0;

foreach ($templateButtons as $button) {
    if (strpos($createContent, $button) !== false) {
        $createButtonCount++;
    }
    if (strpos($editContent, $button) !== false) {
        $editButtonCount++;
    }
}

echo "   📊 Create page template buttons: $createButtonCount/" . count($templateButtons) . "\n";
echo "   📊 Edit page template buttons: $editButtonCount/" . count($templateButtons) . "\n";

if ($createButtonCount === $editButtonCount && $createButtonCount === count($templateButtons)) {
    echo "   ✅ Template buttons match\n";
} else {
    echo "   ❌ Template buttons don't match\n";
    $allMatched = false;
}

echo "\n3. 🎯 JavaScript Functions Check:\n";

$jsFunctions = [
    'function insertTemplate',
    'function insertTinyMCETemplate',
    'function insertPlainTemplate',
    'function testTemplateInsertion',
    'function debugEditorState',
    'function setupTemplateButtons'
];

$createJsCount = 0;
$editJsCount = 0;

foreach ($jsFunctions as $func) {
    if (strpos($createContent, $func) !== false) {
        $createJsCount++;
    }
    if (strpos($editContent, $func) !== false) {
        $editJsCount++;
    }
}

echo "   📊 Create page JS functions: $createJsCount/" . count($jsFunctions) . "\n";
echo "   📊 Edit page JS functions: $editJsCount/" . count($jsFunctions) . "\n";

if ($createJsCount === $editJsCount && $createJsCount === count($jsFunctions)) {
    echo "   ✅ JavaScript functions match\n";
} else {
    echo "   ❌ JavaScript functions don't match\n";
    $allMatched = false;
}

echo "\n4. 🎨 CSS Styles Check:\n";

$cssElements = [
    '.editor-toolbar',
    '.autosave-indicator',
    '.draft-recovery',
    '.editor-loading',
    '.editor-ready'
];

$createCssCount = 0;
$editCssCount = 0;

foreach ($cssElements as $css) {
    if (strpos($createContent, $css) !== false) {
        $createCssCount++;
    }
    if (strpos($editContent, $css) !== false) {
        $editCssCount++;
    }
}

echo "   📊 Create page CSS styles: $createCssCount/" . count($cssElements) . "\n";
echo "   📊 Edit page CSS styles: $editCssCount/" . count($cssElements) . "\n";

if ($createCssCount === $editCssCount && $createCssCount === count($cssElements)) {
    echo "   ✅ CSS styles match\n";
} else {
    echo "   ❌ CSS styles don't match\n";
    $allMatched = false;
}

echo "\n5. 🔄 Debug Tools Check:\n";

$debugTools = [
    'Test Template',
    'Debug Info',
    'testTemplateInsertion()',
    'debugEditorState()'
];

$createDebugCount = 0;
$editDebugCount = 0;

foreach ($debugTools as $debug) {
    if (strpos($createContent, $debug) !== false) {
        $createDebugCount++;
    }
    if (strpos($editContent, $debug) !== false) {
        $editDebugCount++;
    }
}

echo "   📊 Create page debug tools: $createDebugCount/" . count($debugTools) . "\n";
echo "   📊 Edit page debug tools: $editDebugCount/" . count($debugTools) . "\n";

if ($createDebugCount === $editDebugCount && $createDebugCount === count($debugTools)) {
    echo "   ✅ Debug tools match\n";
} else {
    echo "   ❌ Debug tools don't match\n";
    $allMatched = false;
}

echo "\n6. 📋 Advanced Editor Config Check:\n";

if (strpos($createContent, 'advancedEditorConfig') !== false) {
    echo "   ✅ Create page: Advanced editor config found\n";
} else {
    echo "   ❌ Create page: Advanced editor config not found\n";
    $allMatched = false;
}

if (strpos($editContent, 'advancedEditorConfig') !== false) {
    echo "   ✅ Edit page: Advanced editor config found\n";
} else {
    echo "   ❌ Edit page: Advanced editor config not found\n";
    $allMatched = false;
}

echo "\n";

// Summary
echo "📊 CONSISTENCY SUMMARY:\n";
echo "=======================\n";

if ($allMatched) {
    echo "✅ ALL CHECKS PASSED - Edit page is consistent with create page!\n";
    echo "✅ Both pages use the same TinyMCE configuration\n";
    echo "✅ Both pages have identical template button systems\n";
    echo "✅ Both pages have the same JavaScript functions\n";
    echo "✅ Both pages have matching CSS styles\n";
    echo "✅ Both pages have debug tools available\n\n";
    
    echo "🚀 READY FOR TESTING:\n";
    echo "1. Test /admin/articles/create - should work without warnings\n";
    echo "2. Test /admin/articles/{id}/edit - should work identically\n";
    echo "3. Template buttons should work on both pages\n";
    echo "4. Debug tools should be available on both pages\n";
} else {
    echo "❌ INCONSISTENCIES FOUND\n";
    echo "🔧 Some features may not work identically between pages\n";
    echo "🔧 Please review the failed checks above\n";
}

echo "\n✅ Edit page consistency check complete!\n";
?>
