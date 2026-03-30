# Docker Setup untuk CodeIgniter 4 + Tailwind CSS

Panduan lengkap menjalankan project dengan Docker Compose.

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) (versi 20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (versi 2.0+)

## Quick Start

### 1. Setup Environment

Copy file environment:

```bash
cp env .env
```

File `.env` sudah ter-configure untuk Docker.

### 2. Clean Dependencies (First Time Only)

Sebelum build pertama kali, hapus dependency files jika ada:

```bash
docker-compose down -v
rm -rf vendor node_modules package-lock.json composer.lock
```

### 3. Jalankan Docker Compose

```bash
# Build dan jalankan semua service
docker-compose up -d --build

# Tunggu proses build selesai (~2-3 menit untuk first build)
docker-compose logs -f app
```

### 4. Install Dependencies Manually

Karena folder di-mount sebagai volume, perlu manual install di dalam container:

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install Node dependencies
docker-compose exec app npm install
```

### 5. Build Tailwind CSS

```bash
# Build CSS
docker-compose exec app npm run build:css

# Atau jalankan watcher
docker-compose up -d node
```

### 6. Akses Aplikasi

- **Website**: http://localhost
- **Database**: localhost:3306
  - Username: `ci4_user`
  - Password: `password`
  - Database: `ci4`
- **phpMyAdmin**: http://localhost:8081

---

## Penjelasan Technical

### Kenapa Manual Install Diperlukan?

Dockerfile sudah di-configure untuk auto-install dependencies, tetapi karena:
1. Volume mount mengganti folder di dalam container
2. Host machine tidak memiliki vendor/node_modules sebelumnya
3. Docker Compose mount `./` ke `/var/www/html`

Solusinya adalah menjalankan install commands SETELAH container jalan, sehingga dependencies ter-install di dalam container yang sudah ter-mount.

### Proses Build Tahap Demi Tahap

```
1. docker-compose up -d --build
   ↓
   Membangun image PHP dengan all extensions
   ↓
   
2. docker-compose exec app composer install
   ↓
   Install PHP dependencies ke /var/www/html/vendor
   ↓
   
3. docker-compose exec app npm install
   ↓
   Install Node dependencies ke /var/www/html/node_modules
   ↓
   
4. docker-compose exec app npm run build:css
   ↓
   Build Tailwind CSS → public/css/output.css
   ↓
   
✅ Aplikasi siap diakses
```

---

## Command Dasar

```bash
# Lihat status semua container
docker-compose ps

# Lihat logs real-time
docker-compose logs -f

# Lihat logs spesifik service
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f nginx
docker-compose logs -f node

# Jalankan CodeIgniter command
docker-compose exec app php spark migrate
docker-compose exec app php spark seed:run
docker-compose exec app php spark tinker

# Tailwind CSS
# Build CSS
docker-compose exec app npm run build:css

# Atau jalankan watcher untuk auto-rebuild saat ada perubahan
docker-compose up -d node
```

### 4. Akses Aplikasi

- **Website**: http://localhost
- **Database**: localhost:3306
  - Username: `ci4_user`
  - Password: `password`
  - Database: `ci4`
- **phpMyAdmin** (development only): http://localhost:8081


## Command Dasar

```bash
# Lihat status semua container
docker-compose ps

# Lihat logs real-time
docker-compose logs -f

# Lihat logs spesifik service
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f nginx
docker-compose logs -f node

# Jalankan CodeIgniter command
docker-compose exec app php spark migrate
docker-compose exec app php spark seed:run
docker-compose exec app php spark tinker

# Tailwind CSS
docker-compose exec app npm run build:css      # Build sekali
docker-compose up -d node                      # Jalankan watcher
docker-compose logs -f node                    # Lihat Tailwind logs

# Jalankan tests
docker-compose exec app ./vendor/bin/phpunit

# Stop semua service
docker-compose down

# Stop dan hapus volumes (reset database)
docker-compose down -v

# Rebuild container (setelah perubahan Dockerfile)
docker-compose up -d --build

# Restart service tertentu
docker-compose restart app
docker-compose restart db
docker-compose restart nginx
```

## Development Workflow

### Frontend Development (Tailwind CSS)

Setup untuk development dengan auto-rebuild CSS:

```bash
# 1. Terminal 1 - Jalankan PHP & Database
docker-compose up -d app db nginx

# 2. Terminal 2 - Jalankan Tailwind watcher
docker-compose up node

# Atau dalam satu command
docker-compose up -d app db nginx node
```

File CSS akan auto-rebuild saat ada perubahan di file `.php` yang di-scan oleh Tailwind.

Konfigurasi Tailwind:
- Input: `app/Views/css/input.css`
- Output: `public/css/output.css`
- Config: `tailwind.config.js`

### Backend Development (PHP/CodeIgniter)

Edit file PHP di `app/` folder, perubahan langsung ter-reflect di browser tanpa perlu rebuild.

Untuk test:
```bash
docker-compose exec app ./vendor/bin/phpunit
docker-compose exec app php spark test
```

### Code Editing

Semua file project di-mount sebagai volume, sehingga:
- Edit PHP files → langsung ter-reflect tanpa restart
- Edit CSS/Tailwind → auto-rebuild jika node service running
- Edit Dockerfile → perlu rebuild dengan `docker-compose up -d --build`

### Database Management

#### Migrate Database

```bash
docker-compose exec app php spark migrate
```

#### Seed Database

```bash
docker-compose exec app php spark db:seed SeedName
```

#### Access MySQL Console

```bash
docker-compose exec db mysql -u ci4_user -p ci4
# Password: password
```

## Environment Configuration

### .env Configuration

File `.env` sudah di-copy dari `env` dan ter-configure untuk Docker:

```env
CI_ENVIRONMENT=development

# APP Configuration
app.baseURL=http://localhost/
app.forceGlobalSecureRequests=false
app.CSPEnabled=false

# DATABASE - pointing ke 'db' service di docker-compose
database.default.hostname=db
database.default.database=ci4
database.default.username=ci4_user
database.default.password=password
database.default.DBDriver=MySQLi
database.default.port=3306

# SESSION & LOGGER
session.driver=CodeIgniter\Session\Handlers\FileHandler
logger.threshold=4
```

**Important**: 
- `.env` file di-excluded dari git (`.gitignore`)
- Gunakan untuk local development settings
- Jangan commit ke repository

### Customize Configuration

Edit `.env` untuk mengubah:
```env
app.baseURL=http://localhost:8080/          # Ubah port
database.default.password=my_secure_password # Ubah DB password
```

Edit `docker-compose.yml` untuk mengubah port expose:
```yaml
services:
  nginx:
    ports:
      - "8080:80"  # Ubah dari 80 ke 8080
```

## Troubleshooting

### Dependencies tidak ter-install (vendor/node_modules kosong)

Jika setelah build vendor atau node_modules masih kosong:

```bash
# Full reset
docker-compose down -v
rm -rf vendor node_modules package-lock.json composer.lock

# Rebuild
docker-compose up -d --build

# Manual install di dalam container
docker-compose exec app composer install
docker-compose exec app npm install

# Verify
docker-compose exec app ls -la vendor/ | head -3
docker-compose exec app ls -la node_modules/@tailwindcss | head -3
```

### "Failed to open stream" / Missing vendor folder

Error: `Failed opening required '/var/www/html/vendor/codeigniter4/framework/...`

Solusi:
```bash
docker-compose exec app composer install
```

### Port sudah digunakan

```bash
# Lihat port yang digunakan
netstat -tlnp | grep LISTEN

# Atau ubah port di docker-compose.yml
ports:
  - "8080:80"  # Ubah dari 80 ke 8080
```

### Container tidak mau start

```bash
# Lihat error logs
docker-compose logs app

# Rebuild container
docker-compose down -v
docker-compose up -d --build
```

### Database connection error

```bash
# Tunggu database ready
docker-compose exec db mysqladmin ping -h localhost -u ci4_user -p

# Lihat database logs
docker-compose logs db
```

### Permission denied di writable folder

```bash
# Set permissions di local machine
chmod -R 777 writable/
docker-compose down -v
docker-compose up -d --build
```

### Tailwind CSS tidak ter-build

```bash
# Jalankan build manual
docker-compose exec app npm run build:css

# Atau jalankan watcher
docker-compose exec node npm run watch:css
```

## Production Deployment

Untuk production, gunakan environment berbeda:

```bash
# Setup production environment
cp .env.docker .env.production

# Edit .env.production dengan konfigurasi production
APP_ENV=production
CI_ENVIRONMENT=production
```

```bash
# Jalankan dengan production config
docker-compose -f docker-compose.yml up -d
```

**Penting**:

- Jangan gunakan `.override.yml` di production
- Set encryption key di `.env`
- Aktifkan HTTPS dengan reverse proxy (nginx/traefik)
- Gunakan proper database backup strategy

## Network

Semua services terhubung via Docker network `ci4_network`:

- `app` - PHP-FPM service
- `db` - MySQL database (hostname: `db`)
- `nginx` - Web server
- `node` - Node.js for Tailwind (dev)
- `phpmyadmin` - Database GUI (dev only)

## Volumes

- `./` - Project root di-mount ke `/var/www/html`
- `./writable` - Writable directory dengan proper permissions
- `db_data` - MySQL data persistence

## Tips & Tricks

### Build Tailwind hanya sekali (production)

Edit `docker-compose.yml`:

```yaml
node:
  command: npm run build:css  # Ganti watch dengan build
```

### Jalankan specific version PHP/MySQL

```yaml
app:
  image: php:8.1-fpm-alpine  # Ubah versi

db:
  image: mysql:5.7  # Ubah versi
```

### Add services baru

Edit `docker-compose.yml` dan add service baru, misal Redis:

```yaml
redis:
  image: redis:7-alpine
  container_name: ci4_redis
  networks:
    - ci4_network
```

## Git & Version Control

### Files yang di-ignore

File-file berikut sudah di-`.gitignore` dan tidak perlu di-commit:

```
.env                    # Environment configuration (local)
.npm/                   # npm cache
node_modules/           # Node dependencies
vendor/                 # Composer dependencies
writable/               # Generated files
docker-compose.override.yml  # Local overrides
.vscode/                # VS Code settings (local)
.idea/                  # PhpStorm settings (local)
```

### Recommended Git Workflow

```bash
# Clone repository
git clone <repo-url>
cd project

# Setup environment
cp env .env
# Edit .env sesuai local setup Anda

# Build dan run
docker-compose up -d --build

# Build Tailwind
docker-compose exec app npm run build:css

# Commit hanya file yang penting
git add app/ docker/ Dockerfile docker-compose.yml README.Docker.md
git add tailwind.config.js package.json composer.json
# .env, node_modules, vendor tidak perlu di-commit
```

### Pre-commit Checklist

Sebelum push ke repository:

- [ ] `.env` file tidak di-include
- [ ] `node_modules/` dan `vendor/` tidak di-include
- [ ] `public/css/output.css` di-rebuild terbaru
- [ ] `.gitignore` sudah update
- [ ] Docker configuration files ter-include (Dockerfile, docker-compose.yml, dll)

## Support & Issues

Jika mengalami masalah, cek:

1. Docker logs: `docker-compose logs -f service_name`
2. Container status: `docker-compose ps`
3. Network connectivity: `docker-compose exec app ping db`
4. Rebuild container: `docker-compose down -v && docker-compose up -d --build`

---

**Happy Coding! 🚀**
