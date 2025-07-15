# LAMDAKU CMS - Missing Artisan ONE-LINER Fix

## 🚨 SUPER QUICK FIX (Copy-Paste This)

Jika error "Could not open input file: artisan", copy-paste command ini:

```bash
cd /home/u329849080/domains/lamdaku.com/public_html && rm -rf * .??* && git clone https://github.com/lipamitranusa/lamdakubackend.git . && chmod +x artisan && ls -la artisan
```

## Alternative jika git tidak ada:

```bash
cd /home/u329849080/domains/lamdaku.com/public_html && rm -rf * .??* && wget https://github.com/lipamitranusa/lamdakubackend/archive/refs/heads/main.zip && unzip main.zip && mv lamdakubackend-main/* . && mv lamdakubackend-main/.* . && rm -rf lamdakubackend-main main.zip && chmod +x artisan && ls -la artisan
```

## Setelah itu jalankan:

```bash
composer install --no-dev --optimize-autoloader
cp .env.production .env
nano .env  # Update DB_PASSWORD
php artisan key:generate
php artisan migrate
```

## Test:

```bash
php artisan --version
curl https://lamdaku.com/api
```
