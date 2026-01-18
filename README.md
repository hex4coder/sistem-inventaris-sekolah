<p align="center">
    <h1 align="center">Sistem Inventaris Sekolah (Sarpras)</h1>
    <p align="center">Aplikasi Manajemen Peminjaman dan Inventaris Barang Berbasis Web (Laravel 11)</p>
</p>

## 📝 Deskripsi Aplikasi

**Sistem Inventaris Sekolah** adalah aplikasi web yang dirancang untuk memudahkan sekolah dalam mengelola inventaris sarana dan prasarana (Sarpras). Aplikasi ini menangani pencatatan stok barang, proses peminjaman oleh siswa/guru, persetujuan admin, pengembalian, hingga pelaporan otomatis dalam format PDF.

### Fitur Utama
1.  **Manajemen Barang**: Pencatatan data barang, kategori, lokasi, kondisi (Baik, Rusak, Perbaikan), dan stok.
2.  **Sistem Peminjaman**:
    *   Pengajuan peminjaman oleh User (Guru/Siswa).
    *   Persetujuan (Approval) oleh Admin.
    *   Upload foto bukti **Serah Terima** (saat diambil) dan **Pengembalian** (saat dikembalikan).
    *   Preview foto *fullscreen*.
3.  **Laporan PDF Otomatis**:
    *   Rekapitulasi peminjaman.
    *   Tanda tangan digital (Wakabid Sarpras).
    *   **Lampiran Foto**: Bukti fisik kondisi barang.
    *   **Lampiran Ditolak**: Daftar pengajuan yang ditolak beserta alasannya.
4.  **Dashboard Admin**:
    *   Statistik ringkas (Total Barang, Peminjaman Aktif).
    *   **Grafik Analitik**: Tren peminjaman 30 hari terakhir & Distribusi status peminjaman.
5.  **Multi-Role**: Admin (Full Access) dan User (Peminjam).

---

## 📸 Screenshots

### 1. Dashboard & Analitik
**Grafik Dashboard Admin**
Menampilkan statistik ringkas dan grafik tren peminjaman serta statusnya.
![Grafik Dashboard](screenshot_app/grafik%20dashboard%20admin.png)

### 2. Manajemen Barang & Peminjaman
**Daftar Barang**
![Daftar Barang](screenshot_app/tampilan%20daftar%20barang.png)

**Riwayat Peminjaman**
![Daftar Peminjaman](screenshot_app/daftar%20peminjaman.png)

**Detail Peminjaman**
![Detail Peminjaman](screenshot_app/detail%20peminjaman.png)
![Detail Peminjaman 2](screenshot_app/detail%20peminjaman%202.png)

### 3. Laporan Laporan PDF
**Contoh Laporan PDF**
![Laporan PDF](screenshot_app/contoh%20laporan%20pdf.png)

**Lampiran Foto Bukti**
![Lampiran Foto](screenshot_app/lampiran%20foto%20peminjaman%20dan%20pengembalian.png)

**Lampiran Penolakan**
![Lampiran Ditolak](screenshot_app/lampiran%20peminjaman%20ditolak.png)

### 4. Utilitas Sistem
**Backup & Restore Database**
![Backup Restore](screenshot_app/backup%20dan%20restore.png)

---

## 🚀 Panduan Instalasi (Server Debian 13)

Berikut adalah langkah-langkah instalasi lengkap untuk deployment di server **Debian 13 (Trixie)**.

### 1. Persiapan Server & Dependencies
Update sistem dan install paket yang dibutuhkan (Web Server, PHP, Database).

```bash
# Update repository
sudo apt update && sudo apt upgrade -y

# Install Dependencies (Git, Curl, Unzip, Nginx/Apache)
sudo apt install -y git curl unzip nginx software-properties-common

# Install PHP 8.2 (atau versi terbaru yang support Laravel 11) & Ekstensi
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Instalasi Database (MariaDB)
```bash
# Install MariaDB Server
sudo apt install -y mariadb-server

# Secure Installation (Set root password)
sudo mysql_secure_installation

# Buat Database dan User
sudo mysql -u root -p
```
*Dalam prompt MySQL:*
```sql
CREATE DATABASE inventaris_db;
CREATE USER 'inventaris_user'@'localhost' IDENTIFIED BY 'password_aman_anda';
GRANT ALL PRIVILEGES ON inventaris_db.* TO 'inventaris_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Instalasi Node.js (Untuk Build Assets)
Laravel menggunakan Vite yang membutuhkan Node.js.
```bash
# Install Node.js 20 (LTS)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 4. Setup Aplikasi
Clone repository dan konfigurasi.

```bash
# Masuk ke direktori web
cd /var/www/html

# Clone Repository (Ganti URL dengan repo Anda)
git clone https://github.com/username/sistem-inventaris-sekolah.git
cd sistem-inventaris-sekolah

# Install PHP Dependencies
composer install --optimize-autoloader --no-dev

# Copy Environment File
cp .env.example .env

# Edit .env dan sesuaikan database
nano .env
```
*Ubah konfigurasi berikut:*
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://domain-sekolah-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_db
DB_USERNAME=inventaris_user
DB_PASSWORD=password_aman_anda
```

### 5. Finalisasi & Build
```bash
# Generate Application Key
php artisan key:generate

# Migrasi Database & Seeding (Akun Admin Default)
php artisan migrate --seed

# Create Storage Link (Untuk foto bukti)
php artisan storage:link

# Install Node Modules & Build Assets (CSS/JS)
npm install
npm run build

# Atur Permission Folder
sudo chown -R www-data:www-data /var/www/html/sistem-inventaris-sekolah
sudo chmod -R 775 /var/www/html/sistem-inventaris-sekolah/storage
sudo chmod -R 775 /var/www/html/sistem-inventaris-sekolah/bootstrap/cache
```

### 6. Konfigurasi Web Server (Nginx)
Buat file konfigurasi Nginx baru.

```bash
sudo nano /etc/nginx/sites-available/inventaris
```
*Isi konfigurasi:*
```nginx
server {
    listen 80;
    server_name domain-sekolah-anda.com;
    root /var/www/html/sistem-inventaris-sekolah/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Aktifkan Site & Restart Nginx:**
```bash
sudo ln -s /etc/nginx/sites-available/inventaris /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Selesai! 🎉
Akses aplikasi melalui browser di `http://domain-sekolah-anda.com`.
- **Email Default Admin**: `admin@example.com` (jika menggunakan seeder)
- **Password**: `password` (jika menggunakan seeder)

---
*Dibuat dengan ❤️ oleh Tim Pengembang Sekolah*
