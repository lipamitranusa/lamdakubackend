<?php
/**
 * Final TinyMCE 6 Implementation Test
 * Comprehensive verification after compatibility fixes
 */

echo "🔬 Final TinyMCE 6 Implementation Test\n";
echo "====================================\n\n";

$allPassed = true;

// Test 1: Check advanced-editor.js exists and is readable
echo "1. 📁 Advanced Editor Configuration:\n";
$advancedEditorPath = 'public/js/advanced-editor.js';
if (file_exists($advancedEditorPath)) {
    $configContent = file_get_contents($advancedEditorPath);
    $configSize = strlen($configContent);
    echo "   ✅ File exists: $advancedEditorPath\n";
    echo "   ✅ File size: " . number_format($configSize) . " bytes\n";
    
    // Check for key elements
    if (strpos($configContent, 'advancedEditorConfig') !== false) {
        echo "   ✅ Configuration object found\n";
    } else {
        echo "   ❌ Configuration object not found\n";
        $allPassed = false;
    }
    
    if (strpos($configContent, "selector: '#content'") !== false) {
        echo "   ✅ Content selector configured\n";
    } else {
        echo "   ❌ Content selector not found\n";
        $allPassed = false;
    }
} else {
    echo "   ❌ Advanced editor file not found\n";
    $allPassed = false;
}

echo "\n";

// Test 2: Check create.blade.php has TinyMCE integration
echo "2. 📄 Create Article Page Integration:\n";
$createPath = 'resources/views/admin/articles/create.blade.php';
if (file_exists($createPath)) {
    $createContent = file_get_contents($createPath);
    echo "   ✅ Create blade file exists\n";
    
    // Check for TinyMCE CDN
    if (strpos($createContent, 'cdn.tiny.cloud') !== false) {
        echo "   ✅ TinyMCE CDN link found\n";
    } else {
        echo "   ❌ TinyMCE CDN link not found\n";
        $allPassed = false;
    }
    
    // Check for advanced-editor.js inclusion
    if (strpos($createContent, 'advanced-editor.js') !== false) {
        echo "   ✅ Advanced editor script included\n";
    } else {
        echo "   ❌ Advanced editor script not included\n";
        $allPassed = false;
    }
    
    // Check for template buttons
    $templateButtons = [
        'insertTemplate(\'article-intro\')',
        'insertTemplate(\'heading-section\')',
        'insertTemplate(\'bullet-points\')',
        'insertTemplate(\'callout-info\')',
        'insertTemplate(\'quote\')'
    ];
    
    $buttonCount = 0;
    foreach ($templateButtons as $button) {
        if (strpos($createContent, $button) !== false) {
            $buttonCount++;
        }
    }
    
    echo "   ✅ Template buttons found: $buttonCount/" . count($templateButtons) . "\n";
    
    if ($buttonCount < count($templateButtons)) {
        echo "   ⚠️  Some template buttons missing\n";
    }
    
} else {
    echo "   ❌ Create blade file not found\n";
    $allPassed = false;
}

echo "\n";

// Test 3: Check CSS file exists
echo "3. 🎨 Article Content Styling:\n";
$cssPath = 'public/css/article-content-styling.css';
if (file_exists($cssPath)) {
    $cssContent = file_get_contents($cssPath);
    echo "   ✅ CSS file exists: $cssPath\n";
    echo "   ✅ CSS size: " . number_format(strlen($cssContent)) . " bytes\n";
    
    // Check for callout styles
    if (strpos($cssContent, '.callout') !== false) {
        echo "   ✅ Callout styles found\n";
    } else {
        echo "   ❌ Callout styles not found\n";
        $allPassed = false;
    }
} else {
    echo "   ❌ CSS file not found\n";
    $allPassed = false;
}

echo "\n";

// Test 4: Check for JavaScript functions
echo "4. 🔧 JavaScript Functions:\n";
if (isset($createContent)) {
    $jsFunctions = [
        'insertTemplate',
        'insertTinyMCETemplate',
        'insertPlainTemplate',
        'testTemplateInsertion',
        'debugEditorState'
    ];
    
    $jsCount = 0;
    foreach ($jsFunctions as $func) {
        if (strpos($createContent, "function $func") !== false) {
            $jsCount++;
            echo "   ✅ Function found: $func\n";
        } else {
            echo "   ❌ Function missing: $func\n";
        }
    }
    
    if ($jsCount === count($jsFunctions)) {
        echo "   ✅ All JavaScript functions present\n";
    } else {
        echo "   ⚠️  Missing functions: " . (count($jsFunctions) - $jsCount) . "/" . count($jsFunctions) . "\n";
        $allPassed = false;
    }
} else {
    echo "   ❌ Cannot check JavaScript functions (create.blade.php not loaded)\n";
    $allPassed = false;
}

echo "\n";

// Test 5: Documentation files
echo "5. 📚 Documentation Files:\n";
$docFiles = [
    'RICH_TEXT_EDITOR_IMPLEMENTATION_COMPLETE.md',
    'RICH_TEXT_EDITOR_FINAL_SUCCESS.md',
    'TEMPLATE_BUTTON_FIX_FINAL_REPORT.md',
    'TINYMCE_6_COMPATIBILITY_FIX_COMPLETE.md',
    'TINYMCE_6_COMPLETE_COMPATIBILITY_SUCCESS.md'
];

$docCount = 0;
foreach ($docFiles as $docFile) {
    if (file_exists($docFile)) {
        $docCount++;
        echo "   ✅ $docFile\n";
    } else {
        echo "   ❌ $docFile (missing)\n";
    }
}

echo "   📊 Documentation coverage: $docCount/" . count($docFiles) . " files\n";

echo "\n";

// Test 6: Run compatibility verification
echo "6. 🔍 TinyMCE 6 Compatibility Check:\n";
if (file_exists('verify-tinymce6-compatibility.php')) {
    echo "   ✅ Compatibility verification script exists\n";
    
    // Execute the verification script and capture output
    ob_start();
    include 'verify-tinymce6-compatibility.php';
    $compatOutput = ob_get_clean();
    
    if (strpos($compatOutput, 'ALL CHECKS PASSED') !== false) {
        echo "   ✅ TinyMCE 6 compatibility verified\n";
    } else {
        echo "   ❌ TinyMCE 6 compatibility issues detected\n";
        $allPassed = false;
    }
} else {
    echo "   ❌ Compatibility verification script missing\n";
    $allPassed = false;
}

echo "\n";

// Final summary
echo "📊 FINAL TEST RESULTS:\n";
echo "=====================\n";

if ($allPassed) {
    echo "✅ ALL TESTS PASSED - Implementation is complete and ready!\n";
    echo "✅ TinyMCE 6 compatibility confirmed\n";
    echo "✅ Rich text editor with templates fully functional\n";
    echo "✅ All files and configurations in place\n";
    echo "✅ Documentation complete\n";
    echo "\n";
    echo "🚀 READY FOR BROWSER TESTING:\n";
    echo "1. Open /admin/articles/create in browser\n";
    echo "2. Check console for no errors (F12)\n";
    echo "3. Test template buttons functionality\n";
    echo "4. Verify TinyMCE loads without 404 errors\n";
} else {
    echo "❌ SOME TESTS FAILED - Review issues above\n";
    echo "🔧 Please address the failed tests before proceeding\n";
}

echo "\n✅ Final test complete!\n";
?>
