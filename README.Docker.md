# Panduan Menjalankan Project dengan Docker

Dokumen ini menjelaskan cara menjalankan project CodeIgniter 4 ini menggunakan Docker Compose.
Fokusnya adalah langkah praktis dari nol sampai aplikasi siap dipakai.

## 1. Prasyarat

Pastikan sudah terpasang:

- Docker Engine
- Docker Compose Plugin (docker compose)

Cek cepat:

```bash
docker --version
docker compose version
```

## 2. Struktur Service Docker

Project ini memakai service berikut:

- app: PHP-FPM untuk menjalankan CodeIgniter
- nginx: web server yang menerima request dari browser
- db: MySQL 8
- node: watcher Tailwind CSS (opsional, aktif untuk development profile)

## 3. Setup Awal

Jalankan dari root project.

### 3.1 Buat file .env

Jika belum ada .env:

```bash
cp env .env
```

### 3.2 Pastikan konfigurasi minimum .env

Periksa nilai berikut di .env:

- CI_ENVIRONMENT = development
- app.baseURL = 'http://localhost/'
- database.default.hostname = db
- database.default.database = ci4
- database.default.username = ci4_user
- database.default.password = password
- database.default.port = 3306

Catatan penting untuk session:

- Jangan isi session.savePath dengan null (string)
- Biarkan kosong/comment agar default framework dipakai

Contoh aman:

```ini
session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
# session.savePath =
```

## 4. Menyalakan Service

### 4.1 Jalankan service utama

```bash
docker compose up -d
```

Ini akan menyalakan:

- app
- db
- nginx

### 4.2 Cek status service

```bash
docker compose ps
```

## 5. Inisialisasi Database

Setelah service hidup, jalankan migrasi dan seeder:

```bash
docker compose exec -T app php spark migrate -all
docker compose exec -T app php spark db:seed DatabaseSeeder
```

Cek daftar tabel:

```bash
docker compose exec -T app php spark db:table
```

## 6. Menjalankan Tailwind CSS

### Opsi A: Build sekali

```bash
npm install
npm run build:css
```

### Opsi B: Auto-watch lewat Docker (disarankan saat development)

```bash
docker compose --profile dev up -d node
```

Cek watcher:

```bash
docker compose --profile dev ps
docker compose logs -f node
```

## 7. Akses Aplikasi

Buka di browser:

- http://localhost

Jika route utama belum sesuai, coba:

- http://localhost/index.php

## 8. Perintah Harian yang Sering Dipakai

Start:

```bash
docker compose up -d
```

Stop:

```bash
docker compose down
```

Stop + hapus volume database:

```bash
docker compose down -v
```

Lihat log app:

```bash
docker compose logs --tail=100 app
```

Masuk shell app:

```bash
docker compose exec app sh
```

## 9. Troubleshooting

### 9.1 Bad Gateway (502)

Gejala:

- Browser menampilkan Bad Gateway

Penyebab umum:

- nginx belum terhubung ke php-fpm app:9000
- container app/nginx belum ready

Cek:

```bash
docker compose ps
docker compose logs --tail=100 nginx
docker compose logs --tail=100 app
```

Perbaikan cepat:

```bash
docker compose restart app nginx
```

### 9.2 ErrorException mkdir(): Permission denied

Gejala:

- Error session saat membuka halaman

Penyebab umum:

- session.savePath di .env diisi null (string)

Perbaikan:

- Comment/hapus session.savePath di .env
- Restart app dan nginx

```bash
docker compose restart app nginx
```

### 9.3 Database error (table tidak ada / query gagal)

Penyebab umum:

- migrasi belum dijalankan

Perbaikan:

```bash
docker compose exec -T app php spark migrate -all
docker compose exec -T app php spark db:seed DatabaseSeeder
```

### 9.4 Tailwind tidak ter-apply

Checklist:

1. Pastikan file public/css/output.css tidak kosong
2. Jalankan build ulang
3. Pastikan node watcher aktif jika development
4. Hard refresh browser (Ctrl+Shift+R)

Perbaikan cepat:

```bash
npm run build:css
docker compose restart nginx app
```

## 10. Alur Start Cepat (Rekomendasi)

Untuk start dari nol:

```bash
cp env .env
docker compose up -d
docker compose exec -T app php spark migrate -all
docker compose exec -T app php spark db:seed DatabaseSeeder
npm install
npm run build:css
docker compose --profile dev up -d node
```

Lalu buka:

- http://localhost

---

Jika ingin environment benar-benar bersih (reset database):

```bash
docker compose down -v
docker compose up -d
docker compose exec -T app php spark migrate -all
docker compose exec -T app php spark db:seed DatabaseSeeder
```