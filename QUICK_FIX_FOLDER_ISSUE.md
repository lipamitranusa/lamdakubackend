# 🚀 QUICK FIX: Git Clone Membuat Folder `lamdakubackend`

## ⚡ MASALAH UMUM
Ketika menjalankan `git clone`, file tidak langsung masuk ke folder API tapi membuat folder `lamdakubackend` baru.

---

## 🔧 SOLUSI CEPAT (COPY-PASTE)

### Jika sudah clone dan ada folder `lamdakubackend`:

```bash
# Pindahkan semua file dari folder lamdakubackend ke root
mv lamdakubackend/* .
mv lamdakubackend/.* . 2>/dev/null || true
rm -rf lamdakubackend
chmod +x artisan
ls -la artisan
echo "✅ Files moved to root directory"
```

### Clone langsung ke direktori saat ini:

```bash
# Hapus folder lamdakubackend jika ada
rm -rf lamdakubackend

# Clone langsung ke direktori saat ini (pakai titik)
git clone https://github.com/lipamitranusa/lamdakubackend.git .

# Set permissions
chmod +x artisan
echo "✅ Project cloned to current directory"
```

### One-liner untuk fix folder issue:

```bash
# Command lengkap untuk fix masalah folder
[ -d "lamdakubackend" ] && { echo "Moving files from lamdakubackend folder..."; mv lamdakubackend/* . 2>/dev/null && mv lamdakubackend/.* . 2>/dev/null || true && rm -rf lamdakubackend && echo "✅ Files moved"; } || { echo "No lamdakubackend folder found"; } && [ -f "artisan" ] && chmod +x artisan && echo "✅ Artisan permissions set" || echo "❌ Artisan not found"
```

---

## 📋 VERIFIKASI SETELAH FIX

```bash
# Cek struktur file yang benar
pwd
ls -la artisan composer.json app/ config/

# Test artisan
php artisan --version
```

**Output yang diharapkan:**
```
-rwxr-xr-x ... artisan
-rw-r--r-- ... composer.json
drwxr-xr-x ... app/
drwxr-xr-x ... config/
Laravel Framework 8.x.x
```

---

## 💡 TIPS UNTUK MASA DEPAN

### Clone yang benar dari awal:

```bash
# Pastikan di direktori yang benar
cd /home/u329849080/domains/lamdaku.com/public_html

# Clone dengan titik di akhir untuk langsung ke direktori saat ini
git clone https://github.com/lipamitranusa/lamdakubackend.git .
```

### Atau gunakan script otomatis:

```bash
# Download script auto-setup
curl -s https://raw.githubusercontent.com/lipamitranusa/lamdakubackend/master/fresh-deploy.php | php
```
