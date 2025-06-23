# 🎨 PANDUAN UPLOAD LOGO PERUSAHAAN

## 📋 **SPESIFIKASI TEKNIS LOGO**

### **✅ Format yang Didukung:**
- **JPEG** (`.jpg`, `.jpeg`) - Kompatibel universal
- **PNG** (`.png`) - **DIREKOMENDASIKAN** untuk logo dengan transparansi
- **GIF** (`.gif`) - Mendukung animasi sederhana
- **SVG** (`.svg`) - **IDEAL** untuk logo vektor yang scalable

### **📏 Batasan Ukuran:**
| Kriteria | Spesifikasi |
|----------|-------------|
| **Ukuran File Maksimal** | 5MB (5,120 KB) |
| **Dimensi Maksimal** | 2000 x 2000 pixel |
| **Dimensi Minimal** | 50 x 50 pixel |

### **🎯 Rekomendasi Ukuran Optimal:**

#### **Logo Utama (Landscape):**
- **Ukuran:** 300 x 120 pixel
- **Rasio:** 5:2 (landscape)
- **Format:** PNG atau SVG
- **Penggunaan:** Header website, dashboard admin

#### **Logo Kompak (Square):**
- **Ukuran:** 200 x 200 pixel
- **Rasio:** 1:1 (square)
- **Format:** PNG dengan background transparan
- **Penggunaan:** Favicon, social media, mobile app

#### **Logo HD (High Resolution):**
- **Ukuran:** 600 x 240 pixel
- **Rasio:** 5:2 (landscape)
- **Format:** SVG (vector) atau PNG
- **Penggunaan:** Print materials, large displays

---

## 🚨 **TROUBLESHOOTING - Logo Tidak Muncul**

### **Penyebab Umum:**
1. **File Tidak Ada**: File logo terhapus dari storage
2. **Nama File Salah**: Database menyimpan nama file yang tidak sesuai
3. **Permission Error**: Folder storage tidak dapat diakses
4. **Format Tidak Didukung**: File format tidak sesuai validasi
5. **Ukuran Terlalu Besar**: File melebihi batas maksimal

### **✅ Solusi:**

#### **1. Cek File Exists:**
```bash
# Periksa apakah file logo ada di storage
dir storage\app\public\logos\
```

#### **2. Cek Permission Storage:**
```bash
# Pastikan storage link aktif
php artisan storage:link
```

#### **3. Cek Database:**
```sql
SELECT id, company_name, logo FROM company_info;
```

#### **4. Test URL Logo:**
```
http://localhost:8000/storage/logos/nama-file-logo.png
```

---

## 🎨 **TIPS DESAIN LOGO**

### **Logo PNG (Recommended):**
- Background transparan
- Resolusi minimal 300 DPI untuk print
- Optimasi untuk web (compression)

### **Logo SVG (Ideal):**
- Vector graphics (scalable)
- Ukuran file kecil
- Mendukung CSS styling
- Sempurna untuk responsive design

### **Contoh Kode SVG Logo:**
```svg
<svg xmlns="http://www.w3.org/2000/svg" width="300" height="120">
    <rect width="300" height="120" fill="#1e40af" rx="12"/>
    <text x="150" y="70" font-family="Arial" font-size="24" fill="white" text-anchor="middle">
        NAMA PERUSAHAAN
    </text>
</svg>
```

---

## 📁 **STRUKTUR FILE LOGO**

```
storage/app/public/logos/
├── lamdaku-official-logo.svg     # Logo utama (300x120)
├── lamdaku-compact-logo.svg      # Logo kompak (200x80)
├── lamdaku-hd-logo.svg          # Logo HD (600x240)
├── default-logo.svg             # Logo default sistem
└── [uploaded-logos]             # Logo yang diupload user
```

### **Akses Public:**
- **Storage Path:** `storage/app/public/logos/filename.ext`
- **Public URL:** `http://domain.com/storage/logos/filename.ext`
- **Laravel Asset:** `asset('storage/logos/filename.ext')`

---

## 🔧 **VALIDASI UPLOAD**

### **Server-side Validation:**
```php
'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120|dimensions:max_width=2000,max_height=2000'
```

### **Error Messages:**
- **File too large:** Logo maksimal 5MB
- **Invalid format:** Hanya JPEG, PNG, GIF, SVG yang diizinkan
- **Dimensions exceeded:** Maksimal 2000x2000 pixel
- **Upload failed:** Cek permission folder storage

---

## 📱 **RESPONSIVE DISPLAY**

Logo akan otomatis menyesuaikan ukuran di berbagai device:

- **Desktop:** Max-width 200px
- **Tablet:** Max-width 150px  
- **Mobile:** Max-width 120px
- **Fallback:** Icon building jika logo gagal load

---

**📝 Update:** June 12, 2025  
**💡 Tips:** Gunakan SVG untuk hasil terbaik di semua ukuran layar!
