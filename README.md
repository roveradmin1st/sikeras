# Panduan Instalasi SIKERAS (Sistem Informasi Keuangan Gereja)

Panduan ini ditujukan bagi Anda (klien) yang baru saja melakukan `git clone` dari repositori proyek ini agar aplikasi dapat berjalan dengan normal di komputer Anda (Localhost).

Kegagalan saat pertama kali menjalankan proyek Laravel hasil *clone* biasanya terjadi karena file kredensial database (`.env`) dan pustaka pihak ketiga (`vendor/`) tidak ikut terunggah ke GitHub demi alasan keamanan. Ikuti langkah-langkah di bawah ini untuk menyelesaikannya:

## Persyaratan Sistem (Prerequisites)
Pastikan komputer Anda sudah terinstal:
- **XAMPP** (atau Laragon) dengan **PHP minimal versi 8.1** (sangat disarankan PHP 8.2).
- **Composer** (Package Manager untuk PHP).
- **MySQL** (Sudah berjalan melalui Control Panel XAMPP).

---

## Langkah-langkah Instalasi

### 1. Masuk ke Folder Proyek
Buka terminal/Command Prompt (CMD) Anda dan masuk ke direktori tempat Anda melakukan clone.
```bash
cd sikeras
```

### 2. Instal Pustaka (Dependencies)
Jalankan perintah berikut untuk mengunduh semua pustaka (*packages*) yang dibutuhkan oleh aplikasi (seperti pustaka PDF dan Excel).
```bash
composer install
```
*(Proses ini membutuhkan koneksi internet dan akan memakan waktu beberapa menit).*

### 3. Buat File Konfigurasi Lingkungan (.env)
File konfigurasi tidak ikut di-*clone*. Anda harus membuatnya dari file *template* yang tersedia.
Di terminal (jika Anda memakai Git Bash/Linux/Mac):
```bash
cp .env.example .env
```
*(Atau Anda bisa me-rename secara manual file `.env.example` menjadi `.env` melalui File Explorer).*

### 4. Hasilkan Kunci Aplikasi (App Key)
Aplikasi Laravel membutuhkan kunci enkripsi unik untuk keamanan. Jalankan:
```bash
php artisan key:generate
```

### 5. Atur Konfigurasi Database
Buka file `.env` yang baru saja Anda buat menggunakan *text editor* (Notepad/VS Code). Cari bagian konfigurasi database dan ubah nama databasenya sesuai keinginan Anda (misalnya `db_keuangan_gereja`).

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_keuangan_gereja
DB_USERNAME=root
DB_PASSWORD=
```
*(Biarkan `DB_PASSWORD` kosong jika Anda menggunakan pengaturan bawaan XAMPP).*

### 6. Buat Database Kosong di phpMyAdmin
1. Buka browser dan ketik `http://localhost/phpmyadmin`
2. Buat satu database baru dengan nama yang sama persis dengan yang Anda tulis di `.env` (misal: `db_keuangan_gereja`).

### 7. Jalankan Migrasi Data
Untuk membuat tabel-tabel di dalam database secara otomatis, jalankan perintah ini di terminal:
```bash
php artisan migrate
```
*Opsional: Jika kami telah menyiapkan data dummy awalan (seeding), Anda bisa menjalankannya dengan perintah `php artisan migrate --seed`.*

### 8. Jalankan Server Aplikasi
Langkah terakhir, nyalakan server lokal Laravel dengan perintah:
```bash
php artisan serve
```

Aplikasi SIKERAS sekarang sudah bisa diakses melalui browser Anda di alamat:
**[http://localhost:8000/mahanaim/login](http://localhost:8000/mahanaim/login)**

*(Catatan: `/mahanaim` adalah nama tenant/gereja utama di sistem ini).*
