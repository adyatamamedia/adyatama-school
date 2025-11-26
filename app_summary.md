```markdown
# 🏫 ADYATAMA SCHOOL – Admin Dashboard CMS
> Framework: CodeIgniter 4  
> Frontend: Bootstrap 5 (Admin LTE)  
> Database: MySQL (adyatama_sekolah)  
> Author: Adyatama Media  
> License: Internal Development  

---

## 🎯 Overview

ADYATAMA SCHOOL adalah **Content Management System (CMS)** untuk lembaga pendidikan berbasis CodeIgniter 4,  
menggunakan tema **Admin LTE (Bootstrap 5)** sebagai dashboard utama.

Fitur utama:
- Manajemen artikel, kategori, tag, halaman statis
- Galeri foto/video kegiatan
- Data guru dan staf
- Sistem reaksi, komentar, dan statistik kunjungan
- Pengaturan web & SEO meta
- Role-based Access Control (RBAC)
- Scheduled post & activity log

---

## 🧩 Teknologi

| Layer | Stack |
|-------|--------|
| Backend | CodeIgniter 4 (PHP 8.2) |
| Frontend | Admin LTE (Bootstrap 5) |
| Database | MySQL 8 |
| Editor | Summernote (WYSIWYG) |
| Charts | Chart.js |
| File Manager | CI4 Upload + GD |
| Auth | Session-based, RBAC |
| Scheduler | CI4 CLI Command |
| Version Control | Git |

---

## ⚙️ Database Overview

Database: `adyatama_sekolah`  
Total Tables: **23**

Utama: `users`, `roles`, `permissions`, `posts`, `categories`, `tags`  
Pendukung: `media`, `galleries`, `guru_staff`, `settings`, `seo_overrides`  
Interaksi: `post_reactions`, `comments`, `post_views`  
Sistem: `activity_log`, `scheduled_jobs`

---

## 🧭 Flow Pembangunan Sistem

### 1️⃣ Setup & Environment
- Konfigurasi `.env` → koneksi database  
- Jalankan migrasi dan seed data  
- Uji route dasar CI4  
- Buat helper: `setting()`, `user_can()`  

### 2️⃣ Auth & RBAC
- Buat `AuthController` (login/logout)  
- Implementasi `auth` filter  
- Seed `roles`, `permissions`, `role_permissions`  
- Helper `user_can($perm)`  
- Session berbasis user login  

### 3️⃣ Layout & Tema Volt
- Unduh Volt dari Themesberg  
- Pisahkan layout:  
  - `admin_header.php`  
  - `admin_sidebar.php`  
  - `admin_footer.php`  
  - `admin_base.php`  
- Integrasi Chart.js, Volt.js  
- Tambahkan navbar profile + logout  

### 4️⃣ Dashboard
- Controller: `Admin\Dashboard`  
- Data statistik: total post, total user, recent posts  
- Chart: views 7 hari terakhir  

### 5️⃣ Core Modules
#### 📄 Posts
- CRUD (list, create, edit, delete)
- Summernote WYSIWYG Editor
- Upload featured image
- Category & tag select (Select2)
- Auto slug
- Publish/draft toggle

#### 🗂 Categories
- CRUD sederhana  
- Unique slug validation  

#### 🏷 Tags
- CRUD sederhana atau inline pada post  

#### 🖼 Media
- Upload file (gambar, video, dokumen)
- Auto-generate variant (thumb, medium)
- Modal selector (featured image)

---

### 6️⃣ Supporting Modules
#### 🖼 Galleries
- CRUD + upload multiple image  
- Sorting via drag-drop  
- Relasi ke `extracurriculars`

#### 👨‍🏫 Guru-Staff
- CRUD + upload foto  
- Link opsional ke `users`  
- Filter `guru` / `staff`

#### 📄 Pages
- CRUD halaman statis (visi misi, legalitas)
- Editor long text  

#### ⚙ Settings
- CRUD key/value
- Group by: `general`, `contact`, `seo`
- Cache hasil setelah update  

#### 🔍 SEO Overrides
- CRUD meta per post/page  
- Meta title, desc, keywords, canonical  

---

### 7️⃣ Engagement
#### 😍 Reactions
- Tabel: `reaction_types`, `post_reactions`, `post_reaction_counts`  
- Endpoint `/api/posts/{id}/react`  
- Update count via AJAX  

#### 💬 Comments
- CRUD komentar (frontend)  
- Moderasi di admin  

#### 📈 Views
- Middleware untuk logging  
- Statistik di dashboard  

---

### 8️⃣ System Modules
#### 🧾 Activity Log
- Catat aksi user (login, CRUD, delete)
- View log di admin  

#### ⏰ Scheduler
- Command: `php spark publish:scheduled`
- Cron setiap menit
- Otomatis publish post jika waktunya tiba  

---

### 9️⃣ Testing & Deployment
- Tes login & permission
- Validasi semua CRUD
- Tes upload media
- Tes scheduler (auto publish)
- Minify asset (volt.css/js)
- Uji mobile responsive
- Deploy ke hosting / subdomain

---

## ✅ To-Do Summary

| Modul | Status | Catatan |
|--------|---------|----------|
| Setup | ⬜ | Env + DB |
| Auth | ⬜ | RBAC Filter |
| Layout | ⬜ | Volt Integration |
| Dashboard | ⬜ | Statistik |
| Posts | ⬜ | CRUD |
| Categories | ⬜ | CRUD |
| Media | ⬜ | Upload |
| Galleries | ⬜ | Multi Image |
| Guru-Staff | ⬜ | CRUD |
| Pages | ⬜ | CRUD |
| Settings | ⬜ | CRUD |
| SEO | ⬜ | Meta |
| Reactions | ⬜ | API |
| Comments | ⬜ | Moderasi |
| Views | ⬜ | Statistik |
| Activity Log | ⬜ | Tracking |
| Scheduler | ⬜ | Cron job |

---

## 📅 Timeline (Rekomendasi)

| Minggu | Fokus | Target |
|--------|--------|--------|
| 1 | Setup + Auth | Login, RBAC |
| 2 | Layout + Dashboard | Volt theme |
| 3 | CRUD Posts + Categories | Konten utama |
| 4 | Media + Galleries | File upload |
| 5 | Pages + Guru-Staff | Informasi sekolah |
| 6 | SEO + Reactions + Log | Optimasi |
| 7 | Testing & Deploy | Finalisasi |

---

## 📁 Folder Struktur

```

/app
/Controllers
/Admin
Dashboard.php
Posts.php
Galleries.php
Settings.php
Auth.php
/Models
PostModel.php
CategoryModel.php
TagModel.php
MediaModel.php
/Views
/layout
admin_header.php
admin_sidebar.php
admin_footer.php
admin_base.php
/admin
dashboard.php
posts/
galleries/
pages/
settings/
/public
/assets/volt/
/uploads/

```

---

## 📜 License
- Project: ADYATAMA SCHOOL (© Adyatama Media)  
- Theme: Admin LTE – MIT License by Themesberg  

---