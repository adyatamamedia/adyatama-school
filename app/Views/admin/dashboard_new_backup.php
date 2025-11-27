<?= $this->extend('layout/admin_base_new') ?>

<?= $this->section('content') ?>

<!-- Dashboard Overview -->
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
                            <h6 class="text-uppercase mb-1 opacity-75">Total Post</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['published_posts'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= get_trend_indicator($stats['posts_growth'] ?? 0) ?>
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

        <!-- Total Gallery -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Galeri</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['total_galleries'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="metric-badge metric-positive">
                                    <i class="fas fa-images me-1"></i>Aktif
                                </span>
                                <small class="opacity-75"><?= $stats['total_media'] ?? 0 ?> media</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Guru & Staff -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Guru & Staff</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['users'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= get_trend_indicator($stats['users_growth'] ?? 0) ?>
                                <small class="opacity-75">pertambahan</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Total Views -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Views</h6>
                            <div class="counter fw-bold mb-2" data-target="<?= $stats['views'] ?? 0 ?>">0</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= get_trend_indicator($stats['views_growth'] ?? 0) ?>
                                <small class="opacity-75">30 hari terakhir</small>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Charts Section -->
    <div class="row g-3 mb-4">
        <!-- Visitor Statistics Chart -->
        <div class="col-xl-8 col-lg-7">
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
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Overview Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Distribusi Konten
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="contentChart"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Total konten: <?= ($stats['posts'] ?? 0) + ($stats['pages'] ?? 0) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="row g-3 mb-4">
        <!-- Recent Activities -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h6>
                    <div class="d-flex align-items-center">
                        <small class="text-muted me-3">Total: <?= count($recent_activities ?? []) ?> aktivitas</small>
                        <a href="<?= base_url('dashboard/activity-logs') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 150px;">User</th>
                                    <th style="width: 200px;">Activity</th>
                                    <th>Details</th>
                                    <th style="width: 120px;">IP Address</th>
                                    <th style="width: 150px;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_activities)): ?>
                                    <?php foreach ($recent_activities as $index => $activity): ?>
                                        <?php
                                        // Helper function untuk badge color, icon, and label based on action
                                        $badgeColor = getActivityBadge($activity->action);
                                        $badgeIcon = getActivityIcon($activity->action);
                                        $badgeLabel = ucfirst(str_replace('_', ' ', $activity->action));
                                        ?>
                                        <tr>
                                            <td class="text-muted"><?= $index + 1 ?></td>
                                            <td>
                                                <?php if ($activity->username): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                            <?= strtoupper(substr($activity->username, 0, 2)) ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold small"><?= esc($activity->username) ?></div>
                                                            <?php if ($activity->user_fullname): ?>
                                                                <div class="text-muted" style="font-size: 11px;"><?= esc($activity->user_fullname) ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-secondary text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                            <i class="fas fa-robot"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-muted small">System/Guest</div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $badgeColor ?>" style="font-size: 11px;">
                                                    <i class="<?= $badgeIcon ?> me-1"></i>
                                                    <?= $badgeLabel ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <?= getActivityDescription($activity) ?>
                                                </div>
                                                <?php if ($activity->subject_type && $activity->subject_id): ?>
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-dark border" style="font-size: 10px;">
                                                            <?= esc($activity->subject_type) ?> #<?= esc($activity->subject_id) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-network-wired me-1"></i><?= esc($activity->ip_address) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="small text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    <?= date('d M Y', strtotime($activity->created_at)) ?>
                                                </div>
                                                <div style="font-size: 11px;" class="text-muted">
                                                    <?= time_ago($activity->created_at) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            Belum ada aktivitas terbaru
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-server me-2"></i>System Status
                    </h6>
                </div>
                <div class="card-body">
                    <!-- System Health -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-check text-white fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h6 class="mb-1">System Healthy</h6>
                                <p class="text-muted small mb-0">All services operational</p>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="row g-2 text-center mb-3">
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-success"><?= $stats['posts'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Posts</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-info"><?= $stats['total_pages'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Pages</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-warning"><?= $stats['total_galleries'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Galleries</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-primary"><?= $stats['total_media'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Media</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-success"><?= $stats['users'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Users</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <div class="fw-bold text-info"><?= $stats['pending_comments'] ?? 0 ?></div>
                                    <small class="text-muted d-block">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Stats -->
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-3">Performance</h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Server Load</small>
                                <small class="text-muted">Normal</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 35%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Memory Usage</small>
                                <small class="text-muted">2.1 GB / 8 GB</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 26%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Storage</small>
                                <small class="text-muted">15.3 GB / 100 GB</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 15%"></div>
                            </div>
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