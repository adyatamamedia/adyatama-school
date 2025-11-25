# 🏫 ADYATAMA SCHOOL – Admin Dashboard CMS

ADYATAMA SCHOOL adalah **Content Management System (CMS)** untuk lembaga pendidikan berbasis CodeIgniter 4, menggunakan tema **Admin LTE (Bootstrap 5)** sebagai dashboard utama.

## 🎯 Overview

ADYATAMA SCHOOL adalah sistem manajemen konten yang dirancang khusus untuk kebutuhan lembaga pendidikan. Sistem ini menyediakan antarmuka admin yang intuitif untuk mengelola berbagai aspek informasi sekolah secara efisien.

### Fitur Utama
- Manajemen artikel, kategori, tag, halaman statis
- Galeri foto/video kegiatan
- Data guru dan staf
- Sistem reaksi, komentar, dan statistik kunjungan
- Pengaturan web & SEO meta
- Role-based Access Control (RBAC)
- Scheduled post & activity log

## 🧩 Teknologi

| Layer | Stack |
|-------|--------|
| Backend | CodeIgniter 4 (PHP 8.2) |
| Frontend | Admin LTE (Bootstrap 5) |
| Database | MySQL 8 |
| Editor | CKEditor 5 |
| Charts | Chart.js |
| File Manager | CI4 Upload + GD |
| Auth | Session-based, RBAC |
| Scheduler | CI4 CLI Command |
| Version Control | Git |

## 📋 Database Schema

Database: `adyatama_sekolah`
Total Tables: **24**

### Tabel Utama
- `users` - Data pengguna sistem
- `roles` - Peran pengguna dalam sistem
- `permissions` - Hak akses berdasarkan peran
- `posts` - Artikel/berita sekolah
- `categories` - Kategori konten
- `tags` - Tag untuk pengelompokan konten
- `media` - File media (gambar, video, dokumen)

### Tabel Pendukung
- `galleries` - Album galeri kegiatan
- `guru_staff` - Data guru dan staf
- `pages` - Halaman statis (visi, misi, legalitas)
- `settings` - Pengaturan sistem
- `seo_overrides` - Pengaturan SEO per halaman
- `extracurriculars` - Data ekstrakurikuler

### Tabel Interaksi
- `post_reactions` - Reaksi terhadap postingan
- `comments` - Komentar dari pengunjung
- `post_views` - Statistik kunjungan
- `subscribers` - Daftar pelanggan notifikasi

### Tabel Sistem
- `activity_log` - Log aktivitas pengguna
- `scheduled_jobs` - Penjadwalan tugas sistem
- `reaction_types` - Jenis reaksi (like, love, etc.)

## ⚙️ Instalasi

### Prasyarat
- PHP 8.2+
- MySQL 8+
- Composer
- Web server (Apache/Nginx)

### Langkah-langkah Instalasi

1. Clone atau download repository ini
2. Masuk ke direktori proyek dan install dependensi:
   ```bash
   composer install
   npm install
   ```

3. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

4. Konfigurasi koneksi database di file `.env`:
   ```
   database.default.hostname = localhost
   database.default.database = adyatama_sekolah
   database.default.username = root
   database.default.password = 
   ```

5. Jalankan migrasi database:
   ```bash
   php spark migrate
   ```

6. Jalankan seeder untuk data awal:
   ```bash
   php spark db:seed
   ```

7. Jalankan server pengembangan:
   ```bash
   php spark serve
   ```

## 🔐 Autentikasi & Hak Akses

Sistem menggunakan Role-Based Access Control (RBAC) untuk mengatur hak akses pengguna:
- Super Admin: Akses penuh ke semua fitur
- Admin: Akses ke manajemen konten dan pengguna
- Editor: Akses ke manajemen artikel dan media
- Kontributor: Hanya bisa membuat dan mengedit konten sendiri

## 📝 Penggunaan

### Dashboard Admin
Dashboard menyediakan ringkasan statistik penting:
- Jumlah postingan terbaru
- Jumlah pengguna terdaftar
- Grafik kunjungan harian
- Aktivitas terbaru dalam sistem

### Manajemen Konten
- **Artikel**: Buat, edit, dan publikasikan artikel dengan editor rich-text
- **Kategori**: Kelompokkan artikel berdasarkan topik
- **Media**: Unggah dan atur file media untuk digunakan dalam konten
- **Galeri**: Kelola album foto dan video kegiatan sekolah

### Pengaturan Sistem
- Pengaturan umum (nama sekolah, alamat, kontak)
- Pengaturan SEO (meta tag, deskripsi, kata kunci)
- Manajemen pengguna dan hak akses

## 🚀 Deployment

Untuk deployment ke production:
1. Pastikan konfigurasi `.env` sesuai dengan server production
2. Jalankan perintah minifikasi asset:
   ```bash
   npm run production
   ```
3. Set hak akses folder `writable` dan `public/uploads` ke mode tulis
4. Konfigurasi cron job untuk penjadwalan postingan otomatis

## 📅 Timeline Pengembangan

| Minggu | Fokus | Target |
|--------|--------|--------|
| 1 | Setup + Auth | Login, RBAC |
| 2 | Layout + Dashboard | Volt theme |
| 3 | CRUD Posts + Categories | Konten utama |
| 4 | Media + Galleries | File upload |
| 5 | Pages + Guru-Staff | Informasi sekolah |
| 6 | SEO + Reactions + Log | Optimasi |
| 7 | Testing & Deploy | Finalisasi |

## 📄 License
- Project: ADYATAMA SCHOOL (© Adyatama Media)
- Theme: Admin LTE – MIT License by Themesberg