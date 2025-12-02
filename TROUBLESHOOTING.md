# Troubleshooting Guide - Role-Based Access Control

## ERR_TOO_MANY_REDIRECTS untuk Role Guru

### Penyebab:
RoleFilter tidak mengizinkan akses ke `/dashboard` (home page) untuk role guru, menyebabkan redirect loop.

### Solusi yang Diterapkan:
✅ **RoleFilter diupdate** untuk mengizinkan guru akses ke dashboard home page:
- `/dashboard` → ✅ Allowed (dashboard home)
- `/dashboard/` → ✅ Allowed (dashboard home)
- `/dashboard/posts` → ✅ Allowed
- `/dashboard/galleries` → ✅ Allowed
- `/dashboard/media` → ✅ Allowed
- `/dashboard/users` → ❌ Redirect dengan error
- `/dashboard/settings` → ❌ Redirect dengan error

### Testing:
1. **Clear browser cache dan cookies**
2. Login dengan user role guru
3. Seharusnya bisa akses dashboard tanpa redirect loop

---

## Jika Masih Ada Masalah:

### 1. Clear Browser Cache
```
Chrome/Edge: Ctrl + Shift + Delete
Firefox: Ctrl + Shift + Del
```
Pilih: Cookies dan Cache, lalu Clear

### 2. Periksa Session
```bash
cd C:\xampp\htdocs\adyatama-school2\dash
php spark cache:clear
```

### 3. Periksa File Writable
Pastikan folder `writable/` memiliki permission yang benar:
```bash
# Windows
icacls writable /grant Everyone:F /t
```

### 4. Debug Mode
Edit `.env` dan aktifkan debug:
```
CI_ENVIRONMENT = development
CI_DEBUG = true
```

### 5. Periksa Session di Database
Jika menggunakan database session, periksa tabel `ci_sessions`:
```sql
SELECT * FROM ci_sessions WHERE id = 'YOUR_SESSION_ID';
```

### 6. Manual Check Role Filter
Tambahkan logging di RoleFilter untuk debug:
```php
// Di app/Filters/RoleFilter.php, method before()
log_message('debug', 'Role: ' . $user->role);
log_message('debug', 'Segment2: ' . $segment2);
log_message('debug', 'URI: ' . $uri->getPath());
```

Kemudian lihat log di: `writable/logs/log-[DATE].log`

---

## Common Issues

### Issue: "You do not have permission" terus muncul
**Penyebab**: Role di session tidak terupdate setelah perubahan di database

**Solusi**:
1. Logout
2. Clear browser cache & cookies
3. Login ulang

### Issue: Menu tidak muncul untuk role tertentu
**Penyebab**: Helper function `can_access_menu()` belum di-load

**Solusi**:
Pastikan `auth_helper.php` di-load di controller atau routes:
```php
helper('auth');
```

### Issue: Redirect loop setelah update code
**Penyebab**: Opcode cache atau session cache

**Solusi**:
1. Restart Apache/Web Server
2. Clear opcode cache jika ada (opcache, APCu)
3. Clear session:
```bash
php spark cache:clear
```

---

## Verifikasi Role Access

### Test untuk setiap role:

**Admin:**
```bash
# Semua URL harus accessible
curl http://localhost/dash/dashboard
curl http://localhost/dash/dashboard/users
curl http://localhost/dash/dashboard/settings
```

**Operator:**
```bash
# Semua kecuali bisa edit/delete admin users
curl http://localhost/dash/dashboard
curl http://localhost/dash/dashboard/users  # ✅ Bisa akses
curl http://localhost/dash/dashboard/posts
```

**Guru:**
```bash
# Hanya posts, galleries, media
curl http://localhost/dash/dashboard          # ✅ Bisa akses
curl http://localhost/dash/dashboard/posts    # ✅ Bisa akses
curl http://localhost/dash/dashboard/galleries # ✅ Bisa akses
curl http://localhost/dash/dashboard/users    # ❌ Redirect
```

---

## Contact & Support

Jika masih ada masalah setelah mengikuti langkah di atas:
1. Periksa log error di `writable/logs/`
2. Periksa browser console untuk JavaScript errors
3. Periksa network tab di browser DevTools untuk melihat redirect chain
