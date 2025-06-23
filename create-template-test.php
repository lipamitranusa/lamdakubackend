<?php
/**
 * Quick Test - Template Button Click Issue
 * Generate test page untuk debug template insertion
 */

echo "🔧 CREATING TEST PAGE FOR TEMPLATE BUTTON DEBUG\n";
echo "==============================================\n\n";

// Create simple test HTML page
$testHtml = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Button Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">🧪 Template Button Test</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Test Buttons -->
                        <div class="mb-3">
                            <h5>Test Template Buttons:</h5>
                            <div class="btn-group-vertical btn-group-sm w-100" role="group">
                                <div class="btn-group btn-group-sm mb-2" role="group">
                                    <button type="button" class="btn btn-outline-primary" onclick="insertTemplate(\'article-intro\')">
                                        <i class="fas fa-paragraph"></i> Intro Artikel
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertTemplate(\'heading-section\')">
                                        <i class="fas fa-heading"></i> Section
                                    </button>
                                    <button type="button" class="btn btn-outline-success" onclick="insertTemplate(\'bullet-points\')">
                                        <i class="fas fa-list"></i> Points
                                    </button>
                                    <button type="button" class="btn btn-outline-info" onclick="insertTemplate(\'callout-info\')">
                                        <i class="fas fa-info-circle"></i> Info Box
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Debug Buttons -->
                        <div class="mb-3">
                            <h5>Debug Tools:</h5>
                            <button type="button" class="btn btn-warning btn-sm me-2" onclick="debugEditorState()">
                                <i class="fas fa-bug"></i> Debug Editor
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="testDirectInsertion()">
                                <i class="fas fa-test-tube"></i> Test Direct
                            </button>
                        </div>
                        
                        <!-- Content Textarea -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Content:</label>
                            <textarea id="content" name="content" rows="15" class="form-control" placeholder="Template akan dimasukkan di sini..."></textarea>
                        </div>
                        
                        <!-- Console Output -->
                        <div class="mb-3">
                            <h5>Console Output:</h5>
                            <div id="console-output" class="border rounded p-3 bg-dark text-light" style="height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                                <div class="text-success">Console ready... Open F12 for full debugging</div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Basic template insertion function for testing
    function insertTemplate(type) {
        console.log("🎯 insertTemplate called with:", type);
        
        const textarea = document.getElementById("content");
        if (!textarea) {
            console.error("❌ Textarea not found!");
            appendToConsole("❌ Textarea not found!");
            return;
        }
        
        let template = "";
        
        switch(type) {
            case "article-intro":
                template = "\\n\\n<div class=\\"article-content\\">\\n    <p class=\\"intro\\"><strong>Paragraf pembuka dengan emphasis yang menarik perhatian pembaca.</strong> Jelaskan secara singkat apa yang akan dibahas dalam artikel ini.</p>\\n</div>\\n\\n";
                break;
            case "heading-section":
                template = "\\n\\n<h2>Judul Bagian Utama</h2>\\n<p>Konten dengan <strong>bold text</strong> dan <em>italic text</em> untuk memberikan penekanan pada poin-poin penting.</p>\\n\\n";
                break;
            case "bullet-points":
                template = "\\n\\n<ul>\\n    <li><strong>Point 1:</strong> Deskripsi detail mengenai poin pertama</li>\\n    <li><strong>Point 2:</strong> Penjelasan komprehensif tentang aspek kedua</li>\\n    <li><strong>Point 3:</strong> Informasi tambahan yang mendukung pemahaman</li>\\n</ul>\\n\\n";
                break;
            case "callout-info":
                template = "\\n\\n<div class=\\"callout info\\"><strong>💡 Tips Penting:</strong> Gunakan callout box seperti ini untuk memberikan informasi tambahan.</div>\\n\\n";
                break;            default:
                template = "\\n\\n<p>Template " + type + " tidak ditemukan.</p>\\n\\n";
        }
        
        const cursorPos = textarea.selectionStart || 0;
        const textBefore = textarea.value.substring(0, cursorPos);
        const textAfter = textarea.value.substring(cursorPos);
        
        textarea.value = textBefore + template + textAfter;
        textarea.focus();
        
        const message = "✅ Template " + type + " inserted successfully!";
        console.log(message);
        appendToConsole(message);
    }
    
    function debugEditorState() {
        console.log("🔍 DEBUGGING EDITOR STATE");
        appendToConsole("🔍 DEBUGGING EDITOR STATE");
        
        const textarea = document.getElementById("content");
        const message = textarea ? "✅ Textarea found" : "❌ Textarea NOT found";
        console.log(message);
        appendToConsole(message);
        
        if (textarea) {
            const info = "📝 Content length: " + textarea.value.length;
            console.log(info);
            appendToConsole(info);
        }
        
        // Check TinyMCE
        if (typeof tinymce !== "undefined") {
            console.log("✅ TinyMCE available");
            appendToConsole("✅ TinyMCE available");
        } else {
            console.log("❌ TinyMCE NOT available");
            appendToConsole("❌ TinyMCE NOT available");
        }
    }
    
    function testDirectInsertion() {
        console.log("🧪 Testing direct insertion");
        appendToConsole("🧪 Testing direct insertion");
        
        const textarea = document.getElementById("content");
        if (textarea) {
            textarea.value += "\\n\\n[DIRECT TEST] This was inserted directly at " + new Date().toLocaleTimeString();
            const message = "✅ Direct insertion successful";
            console.log(message);
            appendToConsole(message);
        } else {
            const message = "❌ Direct insertion failed - no textarea";
            console.log(message);
            appendToConsole(message);
        }
    }
    
    function appendToConsole(message) {
        const consoleOutput = document.getElementById("console-output");
        if (consoleOutput) {
            const time = new Date().toLocaleTimeString();
            const div = document.createElement("div");
            div.innerHTML = `[${time}] ${message}`;
            consoleOutput.appendChild(div);
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        }
    }
    
    // Initialize
    document.addEventListener("DOMContentLoaded", function() {
        console.log("🚀 Test page loaded");
        appendToConsole("🚀 Test page loaded - Click buttons to test");
    });
    </script>
</body>
</html>';

// Save test file
$testFile = 'public/template-button-test.html';
file_put_contents($testFile, $testHtml);

echo "✅ Test page created: $testFile\n";
echo "📋 Access URL: http://localhost/your-project/public/template-button-test.html\n\n";

echo "🧪 TEST STEPS:\n";
echo "1. Open the test page in browser\n";
echo "2. Open F12 Developer Tools → Console\n";
echo "3. Click template buttons one by one\n";
echo "4. Check if templates are inserted\n";
echo "5. Use Debug buttons for troubleshooting\n";
echo "6. Compare with actual create article page\n\n";

echo "💡 If test page works but create page doesn\'t:\n";
echo "- Check for JavaScript conflicts\n";
echo "- Verify TinyMCE loading order\n";
echo "- Check for missing CSS/JS files\n";
echo "- Verify button onclick handlers\n\n";

echo "📅 Test page generated: " . date('Y-m-d H:i:s') . "\n";
