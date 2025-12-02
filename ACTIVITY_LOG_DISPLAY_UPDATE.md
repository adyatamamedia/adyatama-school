# Activity Log Display Update

## Overview
Activity logs sekarang menampilkan **judul/nama** dari subject yang di-CRUD, bukan hanya ID.

## Changes Made

### 1. Updated Helper Function
**File:** `app/Helpers/dashboard_helper.php`
**Function:** `getActivityDescription($log)`

**Improvements:**
- ✅ **Prioritized metadata extraction** - Mencari title/name dengan urutan prioritas:
  1. `title` (untuk post, page, gallery)
  2. `name` (untuk category, extracurricular)
  3. `nama` (untuk guru/staff, student application)
  4. `username` (untuk user)
  5. `email` (untuk subscriber)
  6. `filename` (untuk media)
  7. `slug` (untuk tag)
  8. `caption` (untuk media caption)

- ✅ **Additional badges** - Menampilkan role dan status jika tersedia
- ✅ **Complete action list** - Ditambahkan semua 40+ actions
- ✅ **Styled output** - Menggunakan Bootstrap classes untuk tampilan yang lebih baik

### 2. Display Format

**Before:**
```
Membuat artikel: 123
Menghapus halaman: 45
Update user: 67
```

**After:**
```
Membuat artikel: Panduan Lengkap Laravel 11
Menghapus halaman: Tentang Kami
Update user: john_doe [Role: Admin]
Mengupdate guru/staff: Ahmad Subhan
Upload media: hero-image.jpg
```

## Display Examples

### Posts
```
Membuat artikel: Tips Belajar Laravel
Mengupdate artikel: Tutorial CodeIgniter 4
Menghapus artikel: Draft Article
```

### Pages
```
Membuat halaman: Contact Us
Mengupdate halaman: About Us
Menghapus halaman: Privacy Policy
```

### Galleries
```
Membuat galeri: Event MPLS 2024
Mengupdate galeri: Kegiatan Ekstrakurikuler
Menghapus galeri: Album Lama
```

### Categories
```
Membuat kategori: Tutorial
Mengupdate kategori: Berita
Menghapus kategori: Pengumuman
```

### Users
```
Membuat user: john_doe [Role: admin]
Mengupdate user: jane_smith [Role: guru]
Menghapus user: old_user
```

### Media
```
Upload media: hero-bg.jpg
Mengupdate caption media: Updated Caption
Menghapus media: uploads/old-image.jpg
```

### Guru/Staff
```
Menambah guru/staff: Ahmad Subhan
Mengupdate guru/staff: Siti Nurhaliza
Menghapus guru/staff: Retired Teacher
```

### Extracurriculars
```
Membuat ekstrakurikuler: Basket
Mengupdate ekstrakurikuler: Pramuka
Menghapus ekstrakurikuler: Old Club
```

### Student Applications
```
Update status pendaftaran siswa: [Status: accepted]
Menghapus pendaftaran siswa: Ahmad Rahman
```

### Subscribers
```
Menghapus subscriber: email@example.com
```

### Tags
```
Menghapus tag: laravel-tips
Menghapus tag (bulk): php
```

### Settings
```
Mengupdate pengaturan sistem
```

## Visual Styling

### Primary Text (Title/Name)
```html
<strong class="text-primary">Judul Artikel</strong>
```
- **Color:** Primary blue
- **Weight:** Bold
- Makes the title stand out

### Role Badge
```html
<span class="badge bg-info">admin</span>
```
- **Color:** Info blue
- **Use:** For user roles

### Status Badge
```html
<span class="badge bg-warning text-dark">accepted</span>
```
- **Color:** Warning yellow with dark text
- **Use:** For status info (pending, accepted, rejected, etc)

## Metadata Keys Priority

Function checks metadata in this order:

1. **`title`** - Primary for posts, pages, galleries
2. **`name`** - For categories, extracurriculars
3. **`nama`** - For Indonesian entities (guru/staff, students)
4. **`username`** - For user management
5. **`email`** - For subscribers
6. **`filename`** - For media uploads
7. **`slug`** - For tags
8. **`caption`** - For media caption updates

## Testing

### Test Case 1: Create Post
```php
log_activity('create_post', 'post', $postId, ['title' => 'My New Article']);
```
**Expected Output:**
```
Membuat artikel: My New Article
```

### Test Case 2: Update User with Role
```php
log_activity('update_user', 'user', $userId, [
    'username' => 'john_doe',
    'role' => 'admin'
]);
```
**Expected Output:**
```
Mengupdate user: john_doe [Role: admin]
```

### Test Case 3: Delete Gallery
```php
log_activity('delete_gallery', 'gallery', $galleryId, ['title' => 'Event 2024']);
```
**Expected Output:**
```
Menghapus galeri: Event 2024
```

### Test Case 4: Upload Media
```php
log_activity('upload_media', 'media', $mediaId, ['filename' => 'hero.jpg']);
```
**Expected Output:**
```
Upload media: hero.jpg
```

### Test Case 5: Update Status
```php
log_activity('update_student_application_status', 'student_application', $id, [
    'status' => 'accepted',
    'nama' => 'Ahmad Rahman'
]);
```
**Expected Output:**
```
Update status pendaftaran siswa: Ahmad Rahman [Status: accepted]
```

## Fallback Behavior

If no metadata is found, displays only the action:
```
Membuat artikel (No title available)
```

If metadata exists but no recognized key:
```
Membuat artikel
```

## Benefits

✅ **Better Readability** - Instantly see what was created/updated/deleted
✅ **No Need to Query Database** - All info in metadata
✅ **Context-Aware** - Shows role, status, and other relevant info
✅ **Consistent Format** - All actions follow same pattern
✅ **User-Friendly** - Easy to understand for non-technical users

## View Location

**File:** `app/Views/admin/activity_logs/index.php`

The view uses `getActivityDescription()` helper:
```php
<div class="small">
    <?= getActivityDescription($log) ?>
</div>
```

## Maintenance

To add new actions:
1. Add to `$descriptions` array in `getActivityDescription()`
2. Ensure metadata is logged with appropriate key (title/name/etc)
3. Test the display in activity logs view

## Notes

- HTML is allowed in output (for styling)
- Use `esc()` for security on user input
- Bulk operations may not have titles (shows action only)
- Settings updates show action without specific title

---

**Last Updated:** 2024-11-30
**Version:** 2.0
