# 📁 SIMPLE FTP UPLOAD - LAMDAKU CMS

## 🎯 CARA TERMUDAH: Upload Manual via cPanel

### STEP 1: Download Project
1. Buka: https://github.com/lipamitranusa/lamdakubackend
2. Klik **"Code"** → **"Download ZIP"**
3. Extract di komputer: `lamdakubackend-main/`

### STEP 2: Upload ke Server
1. **Login cPanel** Hostinger
2. **Buka "File Manager"**
3. **Masuk ke:** `domains/lamdaku.com/public_html/`
4. **Hapus file lama** (Select All → Delete)
5. **Upload semua file** dari folder `lamdakubackend-main/`

### STEP 3: Set Permissions
**Via cPanel File Manager:**
- Klik kanan `artisan` → Permissions → **755**
- Klik kanan folder `storage` → Permissions → **755** ✅ Recursive
- Klik kanan folder `bootstrap` → Permissions → **755** ✅ Recursive

### STEP 4: Setup .env
1. **Rename** `.env.production` → `.env`
2. **Edit** file `.env`
3. **Ganti:** `DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE`
4. **Dengan:** `DB_PASSWORD=password_database_asli_anda`
5. **Save**

### STEP 5: Upload & Run Setup Script
1. **Upload** file `manual-setup.php` (sudah ada di project)
2. **Via Terminal/SSH** atau **cPanel Terminal:**
   ```bash
   php manual-setup.php
   ```

### STEP 6: Final Setup (Optional)
**Jika ada akses Terminal:**
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

## 🌐 TEST WEBSITE
- **Basic:** https://lamdaku.com
- **API:** https://lamdaku.com/api
- **Admin:** https://lamdaku.com/admin

---

## 🚨 TROUBLESHOOTING

### "File tidak bisa diupload"
- Upload satu-satu atau per folder
- Gunakan mode Binary untuk file PHP
- Cek koneksi internet

### "Permission denied"
- Set permissions via cPanel File Manager
- artisan: 755, storage: 755, .env: 644

### "Website error 500"
- Cek file .env (password database benar?)
- Cek permissions storage/ dan bootstrap/cache/
- Lihat error log di cPanel → Error Logs

### "Composer not found"
- Upload folder vendor/ manual dari komputer local
- Atau gunakan shared hosting yang support Composer

---

## ✅ CHECKLIST UPLOAD BERHASIL

- [ ] ✅ File `artisan` ada (755)
- [ ] ✅ File `composer.json` ada
- [ ] ✅ Folder `app/`, `config/`, `database/` ada
- [ ] ✅ File `.env` ada (copy dari .env.production)
- [ ] ✅ Permissions storage/ = 755
- [ ] ✅ Database password sudah diupdate di .env
- [ ] ✅ Website bisa diakses: https://lamdaku.com

**Total waktu: ~10-15 menit** ⏱️
