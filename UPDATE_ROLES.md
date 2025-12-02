# Update Roles - Role-Based Access Control

## Perubahan Role & Permissions

### 1. **Admin**
- **Akses**: Full access ke semua menu dan fitur
- **Menu yang bisa diakses**: Semua menu

### 2. **Operator** (dahulu "Staff")
- **Akses**: Full access ke semua menu (termasuk user management dengan batasan)
- **Menu yang bisa diakses**:
  - ✅ Dashboard
  - ✅ Media Library
  - ✅ Manajemen Terms (Kategori, Tags, Ekstrakurikuler)
  - ✅ Manajemen Artikel
  - ✅ Manajemen Halaman
  - ✅ Manajemen Galeri
  - ✅ Pendaftaran
  - ✅ Manajemen Guru/Staff
  - ✅ Komentar
  - ✅ Subscriber
  - ✅ Pengguna (bisa menambah/edit user dengan role guru/operator saja)
  - ✅ Settings
  - ✅ Activity Log

**Batasan untuk Operator di Menu Pengguna:**
- ✅ Bisa melihat semua user
- ✅ Bisa menambahkan user dengan role **Guru** atau **Operator**
- ❌ Tidak bisa menambahkan user dengan role **Admin**
- ❌ Tidak bisa edit/delete user dengan role **Admin**
- ✅ Bisa edit/delete user dengan role Guru atau Operator

### 3. **Guru**
- **Akses**: Terbatas hanya untuk posts dan galleries
- **Menu yang bisa diakses**:
  - ✅ Dashboard
  - ✅ Media Library (untuk upload gambar, **TIDAK bisa hapus media**)
  - ✅ Manajemen Artikel
  - ✅ Manajemen Galeri
  - ❌ Semua menu lainnya (restricted)

**Batasan untuk Guru di Media Library:**
- ✅ Bisa upload gambar/media baru
- ✅ Bisa edit caption media
- ❌ **Tidak bisa delete media** (tombol delete tidak tampil)
- ❌ Tidak ada checkbox untuk bulk delete

---

## Cara Update Database

### Opsi 1: Via Seeder (Recommended)
```bash
cd C:\xampp\htdocs\adyatama-school2\dash
php spark db:seed UpdateRolesSeeder
```

### Opsi 2: Via SQL Manual
Jalankan query berikut di phpMyAdmin atau MySQL client:

```sql
-- Update role 'staff' menjadi 'operator'
UPDATE roles 
SET name = 'operator', 
    description = 'Operator with full access except user management' 
WHERE name = 'staff';

-- Update description role lainnya
UPDATE roles 
SET description = 'Administrator with full system access' 
WHERE name = 'admin';

UPDATE roles 
SET description = 'Teacher with access to posts and galleries only' 
WHERE name = 'guru';
```

---

## File Yang Telah Diubah

1. **Filters**:
   - `app/Filters/AdminFilter.php` - Filter khusus untuk admin
   - `app/Filters/RoleFilter.php` - Filter role-based untuk semua route
   
2. **Helpers**:
   - `app/Helpers/auth_helper.php` - Tambah fungsi `can_access_menu()` dan update `user_can()`

3. **Config**:
   - `app/Config/Filters.php` - Register filter baru
   - `app/Config/Routes.php` - Apply role filter ke dashboard routes

4. **Views**:
   - `app/Views/layout/admin_sidebar.php` - Conditional menu berdasarkan role

5. **Seeders**:
   - `app/Database/Seeds/UpdateRolesSeeder.php` - Seeder untuk update roles

---

## Testing

### Test sebagai Admin:
- Login dengan user admin
- Harus bisa akses semua menu termasuk Pengguna
- Bisa create/edit/delete semua user

### Test sebagai Operator:
- Login dengan user role operator
- Harus bisa akses semua menu TERMASUK Pengguna
- Di halaman users index:
  - Admin users ditampilkan dengan badge merah "Admin"
  - Admin users menampilkan icon lock "Protected" (tidak ada tombol edit/delete)
  - Checkbox bulk delete tidak muncul untuk admin users
  - Bisa edit/delete user dengan role Guru atau Operator
- Saat create/edit user:
  - Dropdown role tidak menampilkan opsi "Admin"
  - Jika coba force assign admin role akan di-reject dengan error

### Test sebagai Guru:
- Login dengan user role guru
- Hanya bisa lihat menu: Dashboard, Artikel, Galeri, Media
- Menu Pengguna tidak tampil di sidebar
- Jika coba akses `/dashboard/users` akan redirect dengan error
- Di Media Library:
  - Tombol "Hapus" bulk delete tidak tampil
  - Checkbox bulk select tidak tampil
  - Tombol delete per item tidak tampil (hanya ada tombol edit)
  - Bisa upload dan edit caption media
  - Jika coba akses URL delete langsung akan di-reject dengan error

---

## Catatan Penting

⚠️ **Setelah update role di database:**
1. Logout dan login kembali untuk refresh session
2. Pastikan role di session sudah terupdate
3. Test akses menu sesuai role masing-masing

✅ **Security**:
- Menu tersembunyi di sidebar untuk role yang tidak berhak
- Route dilindungi dengan RoleFilter
- Redirect otomatis jika akses tidak sah dengan pesan error
