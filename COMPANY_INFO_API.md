# Company Info API Documentation

## Overview
API untuk mengelola informasi perusahaan yang dapat digunakan untuk website company profile. API ini menyediakan endpoints untuk mengambil informasi perusahaan, kontak, dan logo.

## Base URL
```
http://localhost:8000/api/v1
```

## Endpoints

### 1. Get Company Information
Mengambil semua informasi perusahaan yang aktif.

**Endpoint:** `GET /company-info`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "company_name": "PT. LAMDAKU Akreditasi Indonesia",
    "address": "Jl. Akreditasi No. 123, Jakarta Pusat 10110, Indonesia",
    "phone": "021 1234 5678",
    "mobile": "0812 3456 7890",
    "email": "info@lamdaku.co.id",
    "website": "https://www.lamdaku.co.id",
    "logo": null,
    "description": "Perusahaan akreditasi terpercaya",
    "social_media": null,
    "is_active": 1,
    "created_at": "2025-06-11 06:55:05",
    "updated_at": "2025-06-11 06:55:05"
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Informasi perusahaan tidak ditemukan"
}
```

### 2. Get Contact Information
Mengambil informasi kontak perusahaan saja.

**Endpoint:** `GET /company-info/contact`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "phone": "021 1234 5678",
    "mobile": "0812 3456 7890",
    "email": "info@lamdaku.co.id",
    "address": "Jl. Akreditasi No. 123, Jakarta Pusat 10110, Indonesia"
  }
}
```

### 3. Get Company Logo
Mengambil URL logo perusahaan.

**Endpoint:** `GET /company-info/logo`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "logo_url": "http://localhost:8000/storage/logos/logo.png",
    "logo_filename": "logo.png"
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Logo tidak ditemukan"
}
```

## Admin Panel

### Akses Admin Panel
URL: `http://localhost:8000/admin/company`

### Fitur Admin Panel
1. **View Company Info**: Melihat informasi perusahaan saat ini
2. **Create Company Info**: Menambah informasi perusahaan baru
3. **Edit Company Info**: Mengubah informasi perusahaan
4. **Upload Logo**: Mengunggah logo perusahaan
5. **Set Active**: Mengaktifkan informasi perusahaan tertentu

### Form Fields
- **company_name** (required): Nama perusahaan
- **address** (required): Alamat lengkap perusahaan
- **phone** (optional): Nomor telepon
- **mobile** (optional): Nomor HP
- **email** (optional): Email perusahaan
- **website** (optional): Website perusahaan
- **logo** (optional): File logo (JPG, PNG, GIF, SVG, max 2MB)
- **description** (optional): Deskripsi perusahaan
- **is_active** (boolean): Status aktif

## Usage dalam React Frontend

### Install dan Import
```jsx
// Di komponen React
import { useState, useEffect } from 'react';

const ContactComponent = () => {
  const [companyInfo, setCompanyInfo] = useState(null);
  
  useEffect(() => {
    const fetchCompanyInfo = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/v1/company-info/contact');
        const data = await response.json();
        if (data.success) {
          setCompanyInfo(data.data);
        }
      } catch (error) {
        console.error('Error fetching company info:', error);
      }
    };

    fetchCompanyInfo();
  }, []);

  return (
    <div>
      <h3>Kontak Kami</h3>
      <p>Telepon: {companyInfo?.phone}</p>
      <p>HP: {companyInfo?.mobile}</p>
      <p>Email: {companyInfo?.email}</p>
      <p>Alamat: {companyInfo?.address}</p>
    </div>
  );
};
```

## Database Structure

### Table: company_info
```sql
CREATE TABLE company_info (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  phone VARCHAR(20) NULL,
  mobile VARCHAR(20) NULL,
  email VARCHAR(255) NULL,
  website VARCHAR(255) NULL,
  logo VARCHAR(255) NULL,
  description TEXT NULL,
  social_media JSON NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```

## Installation & Setup

### 1. Migration
```bash
php artisan migrate
```

### 2. Seeder (Optional)
```bash
php artisan db:seed --class=CompanyInfoSeeder
```

### 3. Storage Link
```bash
php artisan storage:link
```

### 4. Permissions
Pastikan direktori `storage/app/public/logos` memiliki permission write.

## Error Handling

### Common Errors
- **500 Internal Server Error**: Cek log Laravel di `storage/logs/laravel.log`
- **404 Not Found**: Data company info belum ada atau tidak aktif
- **File Upload Error**: Cek permission direktori storage

### Debugging
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Test API endpoint
curl http://localhost:8000/api/v1/company-info
```

## Security Notes
- API bersifat public untuk frontend (tidak perlu autentikasi)
- Admin panel dilindungi middleware autentikasi
- File upload dibatasi type dan size untuk keamanan
- Validasi input dilakukan di server side

## Contributing
1. Edit model `app/Models/CompanyInfo.php` untuk menambah field
2. Update migration untuk perubahan database schema
3. Update controller `app/Http/Controllers/Api/CompanyInfoController.php` untuk logic API
4. Update views di `resources/views/admin/company/` untuk tampilan admin
