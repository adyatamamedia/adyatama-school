# 📊 Dashboard User Guide

## Akses Dashboard

URL: `http://localhost:8080/admin/dashboard` atau `http://your-domain/admin/dashboard`

## 📍 Struktur Dashboard

### 1. Header Section
- **Welcome Message**: Menampilkan nama lengkap user yang login
- **Last Login**: Waktu terakhir login (format: DD MMM YYYY HH:mm)
- **Status Online**: Indikator status dengan animasi pulse

### 2. Main Statistics Cards (Baris Pertama)

#### 📝 Total Posts
- **Angka Besar**: Total artikel yang sudah dipublish
- **Growth Badge**: Persentase pertumbuhan 30 hari terakhir
  - 🟢 Hijau = Naik
  - 🔴 Merah = Turun
- **Draft Info**: Jumlah artikel draft yang belum dipublish

#### 🖼️ Total Galeri
- **Angka Besar**: Total album galeri
- **Items Badge**: Total foto/video di semua galeri
- **Status**: Badge "Aktif"

#### 👥 Guru & Staff
- **Angka Besar**: Total guru dan staff aktif
- **Breakdown Badges**:
  - 🔵 Badge biru = Jumlah guru
  - 🟤 Badge abu-abu = Jumlah staff
- **Growth**: Persentase pertumbuhan user

#### 👁️ Total Views
- **Angka Besar**: Total views 30 hari terakhir
- **Growth Badge**: Pertumbuhan views
- **Today**: Jumlah views hari ini

### 3. Quick Stats (Baris Kedua)

4 Card mini untuk statistik cepat:
- **📄 Halaman**: Total halaman statis
- **💬 Pending Comments**: Komentar menunggu approval
- **📋 Pendaftaran Pending**: Pendaftaran siswa baru yang menunggu
- **📂 Media Library**: Total file media + ukuran storage

### 4. Charts Section

#### 📈 Statistik Pengunjung (Chart Kiri)
- **Type**: Line chart dengan gradient fill
- **Period**: 7 hari terakhir
- **Data**: Jumlah pengunjung per hari
- **Interaktif**: Hover untuk lihat detail
- **Menu**: Dropdown untuk export/refresh

#### 🥧 Distribusi Konten (Chart Kanan)
- **Type**: Doughnut chart
- **Categories**: Posts, Pages, Galleries, Media
- **Color Coded**: Setiap kategori punya warna berbeda
- **Legend**: Di bawah chart

### 5. Popular Posts & Activity

#### 🔥 Post Terpopuler (Kiri)
- **Tabel 5 artikel**: Yang paling banyak dilihat
- **Kolom**:
  - # = Ranking
  - Judul = Link ke edit post
  - Views = Badge dengan jumlah views
- **Sort**: Otomatis by view_count DESC

#### 📊 Aktivitas (Kanan)
- **Chart Bar**: 7 hari terakhir
- **Grouped by**: Jenis aksi (login, create, update, dll)
- **Interactive**: Hover untuk detail

### 6. Recent Activities & System Status

#### 📜 Aktivitas Terbaru (Kiri)
- **Tabel 10 aktivitas terakhir**
- **Kolom**:
  - User: Avatar + username
  - Action: Badge berwarna
    - 🟢 Hijau = Create
    - 🟡 Kuning = Update
    - 🔴 Merah = Delete
    - 🔵 Biru = Login/Logout
  - Details: Info subject (post #123, dll)
  - Time: Waktu & tanggal
- **Link**: "Lihat Semua" ke activity logs lengkap

#### 📊 System Status (Kanan)
- **Health Badge**: Status sistem
  - ✅ Hijau = Healthy
  - ⚠️ Kuning = Warning
  - ❌ Merah = Error
- **Quick Stats Grid**: 4 box mini
  - Posts, Pages, Galleries, Media count
- **Performance Bars**:
  - Storage Usage: Ukuran file media
  - Content Items: Total konten
  - Active Users: Jumlah user aktif

## 🎨 Color Guide

### Badge Colors
- **🟢 Green (Success)**: Create actions, positive growth
- **🟡 Yellow (Warning)**: Update actions, moderate status
- **🔵 Blue (Info)**: Login/logout, information
- **🔴 Red (Danger)**: Delete actions, negative growth
- **🟤 Gray (Secondary)**: Neutral status

### Card Theme Colors
- **Primary (Purple gradient)**: Posts
- **Success (Green gradient)**: Galleries
- **Info (Blue gradient)**: Guru & Staff
- **Warning (Yellow gradient)**: Views

## 📱 Responsive Behavior

### Desktop (>1200px)
- 4 kolom untuk main stats
- 2 kolom untuk charts (8:4 ratio)
- Full table view

### Tablet (768px - 1200px)
- 2 kolom untuk stats
- Stacked charts
- Scrollable tables

### Mobile (<768px)
- 1 kolom, semua stacked
- Smaller font sizes
- Touch-friendly buttons
- Horizontal scroll tables

## 🔄 Auto-Refresh

Dashboard tidak auto-refresh. Untuk data terbaru:
1. Refresh browser (F5)
2. Atau klik menu dropdown chart → Refresh

## 💡 Tips & Tricks

### 1. Quick Navigation
- Klik judul post di "Post Terpopuler" → langsung ke edit page
- Klik "Lihat Semua" di Activities → ke activity logs lengkap

### 2. Understanding Growth Indicators
- **Positif (🟢 ↑)**: Baik, ada peningkatan
- **Negatif (🔴 ↓)**: Perlu perhatian, ada penurunan
- **Persentase**: Dibanding 30 hari sebelumnya

### 3. System Status Interpretation
- **Storage > 80%**: Perlu cleanup media
- **Pending Comments > 10**: Perlu review
- **Active Users low**: Check user engagement

### 4. Best Practices
- ✅ Check dashboard setiap hari
- ✅ Monitor growth indicators
- ✅ Review pending items (comments, applications)
- ✅ Keep storage usage under 70%
- ✅ Respond to recent activities

## 🐛 Troubleshooting

### Dashboard tidak muncul data
1. Check apakah sudah login
2. Check koneksi database (.env file)
3. Check tabel sudah ada data

### Chart tidak render
1. Check console browser (F12)
2. Pastikan Chart.js loaded
3. Clear browser cache

### Angka tidak sesuai
1. Pastikan timezone server benar
2. Check filter `deleted_at IS NULL`
3. Refresh browser

### Performance lambat
1. Terlalu banyak data di activity_log → cleanup old logs
2. Banyak media → compress images
3. Check server resources

## 🔧 Customization

### Mengubah Jumlah Aktivitas Ditampilkan
Edit `DashboardNew.php` line ~110:
```php
->limit(10)  // Ganti dengan jumlah yang diinginkan
```

### Mengubah Period Chart
Edit `DashboardNew.php`:
```php
$this->getVisitorStats(7)  // Ganti 7 dengan jumlah hari
```

### Menambah Quick Stat Card
Edit `dashboard_new.php`, duplicate section:
```html
<div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card quick-stat-card h-100">
        <!-- Your content -->
    </div>
</div>
```

## 📞 Support

Jika ada masalah:
1. Check dokumentasi: `DASHBOARD_REDESIGN_CHANGELOG.md`
2. Check browser console untuk error
3. Contact development team

## 🔄 Update History

- **v2.0** (Nov 27, 2025): Complete redesign
  - Modern UI dengan gradient cards
  - Enhanced statistics dengan breakdown
  - New charts (Activity, Popular Posts)
  - Responsive design improvement
  - 100% dynamic data

- **v1.0** (Initial): Basic dashboard
  - Simple statistics cards
  - Visitor & content charts
  - Activity log table

---

**Last Updated**: November 27, 2025
**Version**: 2.0
