# TEMPLATE BUTTON DEBUGGING GUIDE
# =================================

## MASALAH: Template button tidak memasukkan konten ke editor

Berdasarkan analisis kode, semua fungsi dan button sudah terkonfigurasi dengan benar. 
Masalah kemungkinan besar adalah pada timing initialization atau JavaScript conflict.

## LANGKAH DEBUGGING:

### 1. TEST STANDALONE PAGE
Buka file test standalone:
```
http://localhost/lamdaku-cms-backend/public/template-button-debug.html
```
- Jika button berfungsi di sini, masalah ada pada integrase Laravel
- Jika tidak berfungsi, masalah pada JavaScript core

### 2. TEST DI HALAMAN ARTIKEL
Buka halaman create artikel:
```
http://localhost/admin/articles/create
```

### 3. BUKA BROWSER CONSOLE (F12)
Periksa console untuk error:
- Klik F12 → Console tab
- Refresh halaman
- Perhatikan error messages

### 4. TEST MANUAL DI CONSOLE
Ketik di browser console:
```javascript
// Test apakah fungsi tersedia
typeof insertTemplate

// Test manual insertion
insertTemplate('article-intro')

// Debug editor state
debugEditorState()

// Test direct textarea insertion
document.getElementById('content').value += '\n\nTest manual insertion\n\n'
```

### 5. KEMUNGKINAN MASALAH DAN SOLUSI:

#### A. JavaScript Error/Conflict
**Symptoms:** Console menunjukkan error
**Solution:** 
- Check console untuk error specifik
- Pastikan tidak ada JavaScript conflict dengan library lain

#### B. TinyMCE Loading Issue
**Symptoms:** Editor tidak initialize dengan benar
**Solution:**
- Check apakah TinyMCE berhasil load
- Ketik `tinymce` di console, harus return object

#### C. Function Scope Issue
**Symptoms:** `insertTemplate is not defined`
**Solution:**
- Fungsi mungkin tidak di global scope
- Try `window.insertTemplate('article-intro')`

#### D. Element ID Conflict
**Symptoms:** Button click tapi tidak ada response
**Solution:**
- Check apakah `document.getElementById('content')` return element
- Verify button onclick attributes

#### E. Timing Issue
**Symptoms:** Button tidak berfungsi immediately after page load
**Solution:**
- Wait beberapa detik setelah page load
- TinyMCE mungkin belum selesai initialize

### 6. MANUAL FIXES:

#### Fix 1: Force Global Function
Ketik di console:
```javascript
window.insertTemplate = function(type) {
    const textarea = document.getElementById('content');
    if (textarea) {
        textarea.value += '\n\nTemplate: ' + type + '\n\n';
        console.log('Template inserted:', type);
    }
}
```

#### Fix 2: Direct Button Event Override
```javascript
document.querySelectorAll('button[onclick*="insertTemplate"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const onclick = this.getAttribute('onclick');
        const match = onclick.match(/insertTemplate\('([^']+)'\)/);
        if (match) {
            insertTemplate(match[1]);
        }
    });
});
```

### 7. ULTIMATE FALLBACK SOLUTION:

Jika semua cara di atas tidak berhasil, gunakan ini sebagai temporary fix:

```javascript
// Paste this entire code in browser console
function forceTemplateInsertion(type) {
    const templates = {
        'article-intro': '<div class="article-content">\\n    <p class="intro"><strong>Paragraf pembuka dengan emphasis yang menarik perhatian pembaca.</strong> Jelaskan secara singkat apa yang akan dibahas dalam artikel ini.</p>\\n</div>',
        'heading-section': '<h2>Judul Bagian Utama</h2>\\n<p>Konten dengan <strong>bold text</strong> dan <em>italic text</em> untuk memberikan penekanan.</p>',
        'bullet-points': '<ul>\\n    <li><strong>Point 1:</strong> Deskripsi detail mengenai poin pertama</li>\\n    <li><strong>Point 2:</strong> Penjelasan komprehensif tentang aspek kedua</li>\\n    <li><strong>Point 3:</strong> Informasi tambahan yang mendukung pemahaman</li>\\n</ul>',
        'callout-info': '<div class="callout info"><strong>💡 Tips Penting:</strong> Gunakan callout box seperti ini untuk memberikan informasi tambahan.</div>',
        'callout-warning': '<div class="callout warning"><strong>⚠️ Perhatian:</strong> Bagian ini berisi informasi penting yang perlu diperhatikan.</div>',
        'quote': '<blockquote>\\n    <p>"Quote atau kutipan menarik yang mendukung pembahasan dalam artikel ini."</p>\\n    <footer>— Sumber Kutipan</footer>\\n</blockquote>',
        'code-example': '<pre><code>// Contoh implementasi kode\\nfunction exampleFunction() {\\n    const result = "Ini adalah contoh kode";\\n    return result;\\n}</code></pre>',
        'step-by-step': '<h2>Langkah-langkah</h2>\\n<ol>\\n    <li><strong>Langkah Pertama:</strong> Penjelasan detail mengenai langkah awal</li>\\n    <li><strong>Langkah Kedua:</strong> Instruksi lanjutan dengan penjelasan yang mudah diikuti</li>\\n    <li><strong>Langkah Ketiga:</strong> Finalisasi proses dengan tips untuk hasil optimal</li>\\n</ol>'
    };
    
    const textarea = document.getElementById('content');
    if (textarea && templates[type]) {
        const cursorPos = textarea.selectionStart || 0;
        const textBefore = textarea.value.substring(0, cursorPos);
        const textAfter = textarea.value.substring(cursorPos);
        
        textarea.value = textBefore + '\\n\\n' + templates[type] + '\\n\\n' + textAfter;
        textarea.focus();
        
        const newCursorPos = cursorPos + templates[type].length + 4;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        
        console.log('✅ Template inserted:', type);
        alert('Template "' + type + '" berhasil ditambahkan!');
    } else {
        console.error('❌ Template not found or textarea missing');
        alert('Error: Template tidak ditemukan atau textarea tidak ada');
    }
}

// Override all buttons
document.querySelectorAll('button[onclick*="insertTemplate"]').forEach(btn => {
    const onclick = btn.getAttribute('onclick');
    const match = onclick.match(/insertTemplate\\('([^']+)'\\)/);
    if (match) {
        const templateType = match[1];
        btn.onclick = function(e) {
            e.preventDefault();
            forceTemplateInsertion(templateType);
            return false;
        };
        console.log('✅ Button override set for:', templateType);
    }
});

console.log('🔧 All template buttons have been overridden with working functions');
```

### 8. REPORTING HASIL:

Setelah testing, laporkan:
1. **Browser yang digunakan:** Chrome/Firefox/Edge/Safari
2. **Console errors:** Copy paste exact error messages
3. **Function availability:** Hasil dari `typeof insertTemplate`
4. **TinyMCE status:** Hasil dari `typeof tinymce`
5. **Element availability:** Hasil dari `document.getElementById('content')`
6. **Test results:** Apakah template berhasil diinsert dengan method manual

### 9. EXPECTED BEHAVIOR:

Ketika button diklik, yang seharusnya terjadi:
1. Console log: "🎯 insertTemplate called with type: [template-name]"
2. Template HTML muncul di textarea/editor
3. Cursor pindah ke akhir template yang diinsert
4. Success indicator muncul (jika TinyMCE aktif)

Jika behavior ini tidak terjadi, laporkan langkah mana yang gagal.
