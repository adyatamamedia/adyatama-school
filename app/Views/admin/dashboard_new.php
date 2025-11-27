<?= $this->extend('layout/admin_base_new') ?>

<?= $this->section('content') ?>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-2">Dashboard Overview</h1>
                <p class="lead mb-0 opacity-75">Selamat datang kembali, <?= esc(current_user()->fullname ?? 'Admin') ?>!</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex align-items-center justify-content-md-end gap-3">
                    <div>
                        <small class="d-block opacity-75">Terakhir login</small>
                        <strong><?= date('d M Y H:i', strtotime(current_user()->last_login ?? 'now')) ?></strong>
                    </div>
                    <div class="pulse">
                        <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                        <span class="ms-1">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="container-fluid mb-4">
    <div class="row">
        <!-- Total Posts -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Posts</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['posts'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="metric-badge metric-positive">
                                    <i class="fas fa-arrow-up me-1"></i><?= $stats['posts_growth'] ?? 12 ?>%
                                </span>
                                <small class="opacity-75">dari bulan lalu</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registered Users -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Guru & Staff</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['users'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="metric-badge metric-positive">
                                    <i class="fas fa-arrow-up me-1"></i><?= $stats['users_growth'] ?? 8 ?>%
                                </span>
                                <small class="opacity-75">pertambahan</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Views</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['views'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="metric-badge metric-positive">
                                    <i class="fas fa-arrow-up me-1"></i><?= $stats['views_growth'] ?? 25 ?>%
                                </span>
                                <small class="opacity-75">bulan ini</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">System Status</h6>
                            <div class="fw-bold mb-2">
                                <i class="fas fa-check-circle me-1"></i>Healthy
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="metric-badge metric-neutral">
                                    <i class="fas fa-server me-1"></i>Normal
                                </span>
                                <small class="opacity-75">All services OK</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Activity Section -->
<div class="container-fluid mb-4">
    <div class="row">
        <!-- Visitor Statistics Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Statistik Pengunjung (7 Hari Terakhir)
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#">Hari Ini</a>
                            <a class="dropdown-item" href="#">Minggu Ini</a>
                            <a class="dropdown-item" href="#">Bulan Ini</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Overview Pie Chart -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Distribusi Konten
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="contentChart"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Total konten: <?= ($stats['posts'] ?? 0) + ($stats['pages'] ?? 0) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Quick Actions -->
<div class="container-fluid mb-4">
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h6>
                    <a href="<?= base_url('dashboard/activity-logs') ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_activities)): ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="activity-item pb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?= esc($activity->description ?? 'Unknown activity') ?></h6>
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-user me-1"></i><?= esc($activity->user_fullname ?? 'System') ?> •
                                            <i class="fas fa-clock me-1 ms-2"></i><?= time_ago($activity->created_at) ?>
                                        </p>
                                        <?php if ($activity->meta): ?>
                                            <p class="small text-muted"><?= esc($activity->meta) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge bg-<?= getActivityBadge($activity->action) ?> float-end">
                                        <?= getActivityIcon($activity->action) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada aktivitas terbaru</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Info -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/posts/new') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-plus fa-2x text-primary mb-2"></i>
                                <h6 class="mb-0">Artikel Baru</h6>
                                <small class="text-muted">Buat postingan</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/media') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-images fa-2x text-success mb-2"></i>
                                <h6 class="mb-0">Media</h6>
                                <small class="text-muted">Upload file</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/galleries/new') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-camera fa-2x text-info mb-2"></i>
                                <h6 class="mb-0">Galeri</h6>
                                <small class="text-muted">Foto kegiatan</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/guru-staff/new') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-user-plus fa-2x text-warning mb-2"></i>
                                <h6 class="mb-0">Guru/Staff</h6>
                                <small class="text-muted">Tambah data</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/pages/new') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-file-alt fa-2x text-secondary mb-2"></i>
                                <h6 class="mb-0">Halaman</h6>
                                <small class="text-muted">Halaman statis</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('dashboard/settings') ?>" class="quick-action-card card h-100 text-center p-3">
                                <i class="fas fa-cog fa-2x text-dark mb-2"></i>
                                <h6 class="mb-0">Settings</h6>
                                <small class="text-muted">Pengaturan</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Status Overview -->
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Ringkasan Konten
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-primary mb-1"><?= $stats['published_posts'] ?? 0 ?></h5>
                            <small class="text-muted">Artikel Dipublikasi</small>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-warning mb-1"><?= $stats['draft_posts'] ?? 0 ?></h5>
                            <small class="text-muted">Draft Artikel</small>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-info mb-1"><?= $stats['total_pages'] ?? 0 ?></h5>
                            <small class="text-muted">Halaman Statis</small>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-success mb-1"><?= $stats['total_galleries'] ?? 0 ?></h5>
                            <small class="text-muted">Galeri</small>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-secondary mb-1"><?= $stats['total_media'] ?? 0 ?></h5>
                            <small class="text-muted">File Media</small>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <h5 class="text-danger mb-1"><?= $stats['pending_comments'] ?? 0 ?></h5>
                            <small class="text-muted">Komentar Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Visitor Statistics Chart
    const visitorCtx = document.getElementById('visitorChart').getContext('2d');
    new Chart(visitorCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($stats['visitor_labels'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) ?>,
            datasets: [{
                label: 'Pengunjung',
                data: <?= json_encode($stats['visitor_data'] ?? [120, 150, 180, 200, 160, 240, 280]) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Content Distribution Chart
    const contentCtx = document.getElementById('contentChart').getContext('2d');
    new Chart(contentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Artikel', 'Halaman', 'Galeri', 'Media'],
            datasets: [{
                data: [
                    <?= $stats['published_posts'] ?? 25 ?>,
                    <?= $stats['total_pages'] ?? 8 ?>,
                    <?= $stats['total_galleries'] ?? 12 ?>,
                    <?= $stats['total_media'] ?? 45 ?>
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});

// Helper functions
function time_ago($datetime) {
    const time = Date.parse($datetime);
    const now = Date.now();
    const diff = Math.floor((now - time) / 1000);

    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return Math.floor(diff / 60) + ' menit yang lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + ' jam yang lalu';
    return Math.floor(diff / 86400) + ' hari yang lalu';
}

// Mock data for development
const mockStats = {
    posts: <?= $stats['posts'] ?? 45 ?>,
    posts_growth: 12,
    users: <?= $stats['users'] ?? 28 ?>,
    users_growth: 8,
    views: <?= $stats['views'] ?? 12450 ?>,
    views_growth: 25,
    published_posts: <?= $stats['published_posts'] ?? 38 ?>,
    draft_posts: <?= $stats['draft_posts'] ?? 7 ?>,
    total_pages: <?= $stats['total_pages'] ?? 12 ?>,
    total_galleries: <?= $stats['total_galleries'] ?? 15 ?>,
    total_media: <?= $stats['total_media'] ?? 124 ?>,
    pending_comments: <?= $stats['pending_comments'] ?? 3 ?>,
    visitor_labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
    visitor_data: [120, 150, 180, 200, 160, 240, 280]
};
</script>

<?= $this->endSection() ?>