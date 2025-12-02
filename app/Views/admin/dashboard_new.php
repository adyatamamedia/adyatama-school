<?= $this->extend('layout/admin_base_new') ?>

<?= $this->section('content') ?>

<?php
// Helper function untuk format size
function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<!-- Dashboard Header -->
<div class="dashboard-header-v2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-4 fw-bold mb-1">Dashboard Overview</h1>
                <p class="text-white mb-0 opacity-75 small">Selamat datang kembali, <?= esc(current_user()->fullname ?? 'Admin') ?>!</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex align-items-center justify-content-md-end gap-3">
                    <div>
                        <small class="d-block opacity-75" style="font-size: 0.75rem;">Terakhir login</small>
                        <strong style="font-size: 0.85rem;"><?= date('d M Y H:i', strtotime(current_user()->last_login ?? 'now')) ?></strong>
                    </div>
                    <div class="pulse">
                        <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                        <span class="ms-1 small">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="container-fluid mb-4 px-0">
    <div class="row g-3">
        <!-- Total Posts Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card-v2 stat-card-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label mb-2 small text-uppercase fw-bold text-muted">
                                <i class="fas fa-newspaper me-2"></i>Total Posts
                            </div>
                            <h2 class="counter-animate mb-2 fs-3 fw-bold" data-target="<?= $stats['posts']['total'] ?? 0 ?>">0</h2>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <?php
                                $growth = $stats['posts']['growth'] ?? 0;
                                $badgeClass = $growth >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                $icon = $growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                                ?>
                                <span class="badge <?= $badgeClass ?>">
                                    <i class="fas <?= $icon ?> me-1"></i><?= abs($growth) ?>%
                                </span>
                                <small class="text-muted">dari 30 hari lalu</small>
                            </div>
                            <?php if (isset($stats['posts']['draft']) && $stats['posts']['draft'] > 0): ?>
                                <small class="text-muted">
                                    <i class="fas fa-edit me-1"></i><?= $stats['posts']['draft'] ?> draft
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="stat-icon-v2">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Galleries Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card-v2 stat-card-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label mb-2 small text-uppercase fw-bold text-muted">
                                <i class="fas fa-images me-2"></i>Total Galeri
                            </div>
                            <h2 class="counter-animate mb-2 fs-3 fw-bold" data-target="<?= $stats['galleries']['total'] ?? 0 ?>">0</h2>
                            <div class="mb-2">
                                <span class="badge bg-info-subtle text-info">
                                    <i class="fas fa-photo-video me-1"></i><?= $stats['galleries']['total_items'] ?? 0 ?> items
                                </span>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </small>
                        </div>
                        <div class="stat-icon-v2">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guru & Staff Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card-v2 stat-card-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label mb-2 small text-uppercase fw-bold text-muted">
                                <i class="fas fa-users me-2"></i>Guru & Staff
                            </div>
                            <h2 class="counter-animate mb-2 fs-3 fw-bold" data-target="<?= $stats['guru_staff']['total'] ?? 0 ?>">0</h2>
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge bg-primary-subtle text-primary">
                                    <i class="fas fa-chalkboard-teacher me-1"></i><?= $stats['guru_staff']['guru'] ?? 0 ?> guru
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <i class="fas fa-user-tie me-1"></i><?= $stats['guru_staff']['staff'] ?? 0 ?> staff
                                </span>
                            </div>
                            <?php
                            $userGrowth = $stats['users']['growth'] ?? 0;
                            if ($userGrowth != 0):
                            ?>
                                <small class="text-muted">
                                    <i class="fas <?= $userGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?> me-1"></i>
                                    <?= abs($userGrowth) ?>% pertumbuhan
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="stat-icon-v2">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card-v2 stat-card-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label mb-2 small text-uppercase fw-bold text-muted">
                                <i class="fas fa-eye me-2"></i>Total Views
                            </div>
                            <h2 class="counter-animate mb-2 fs-3 fw-bold" data-target="<?= $stats['views']['total_30_days'] ?? 0 ?>">0</h2>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <?php
                                $viewGrowth = $stats['views']['growth'] ?? 0;
                                $badgeClass = $viewGrowth >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                $icon = $viewGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                                ?>
                                <span class="badge <?= $badgeClass ?>">
                                    <i class="fas <?= $icon ?> me-1"></i><?= abs($viewGrowth) ?>%
                                </span>
                                <small class="text-muted">30 hari terakhir</small>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-day me-1"></i><?= $stats['views']['today'] ?? 0 ?> hari ini
                            </small>
                        </div>
                        <div class="stat-icon-v2">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="container-fluid mb-4 px-0">
    <div class="row g-3">
        <!-- Pages Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card quick-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="quick-stat-icon bg-primary-subtle text-primary me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-0 counter-simple text-dark fs-4 fw-bold" data-target="<?= $stats['pages']['total'] ?? 0 ?>">0</h3>
                            <small class="text-muted">Halaman</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card quick-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="quick-stat-icon bg-warning-subtle text-warning me-3">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-0 counter-simple text-dark fs-4 fw-bold" data-target="<?= $stats['comments']['pending'] ?? 0 ?>">0</h3>
                            <small class="text-muted">Pending Comments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Applications Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card quick-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="quick-stat-icon bg-success-subtle text-success me-3">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-0 counter-simple text-dark fs-4 fw-bold" data-target="<?= $stats['student_applications']['pending'] ?? 0 ?>">0</h3>
                            <small class="text-muted">Pendaftaran Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Library Card -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card quick-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="quick-stat-icon bg-info-subtle text-info me-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-0 counter-simple text-dark fs-4 fw-bold" data-target="<?= $stats['media']['total'] ?? 0 ?>">0</h3>
                            <small class="text-muted">Media (<?= formatBytes($stats['media']['total_size'] ?? 0) ?>)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="container-fluid mb-4 px-0">
    <div class="row g-3">
        <!-- Visitor Statistics Chart -->
        <div class="col-xl-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase small">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Statistik Pengunjung (7 Hari Terakhir)
                        </h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-refresh me-2"></i>Refresh</a></li>
                            </ul>
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

        <!-- Content Distribution Chart -->
        <div class="col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase small">
                        <i class="fas fa-chart-pie me-2 text-success"></i>
                        Distribusi Konten
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="contentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Posts & Activity Breakdown -->
<div class="container-fluid mb-4 px-0">
    <div class="row g-3">
        <!-- Popular Posts -->
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase small">
                        <i class="fas fa-fire me-2 text-danger"></i>
                        Post Terpopuler
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Judul</th>
                                    <th style="width: 120px;" class="text-center">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stats['popular_posts'])): ?>
                                    <?php foreach ($stats['popular_posts'] as $index => $post): ?>
                                        <tr>
                                            <td class="text-muted"><?= $index + 1 ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/posts/edit/' . $post['id']) ?>" class="text-decoration-none">
                                                    <?= esc($post['title']) ?>
                                                </a>
                                                <br>
                                                <small class="text-muted"><?= date('d M Y', strtotime($post['created_at'])) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i class="fas fa-eye me-1"></i><?= number_format($post['view_count']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                            Belum ada data
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Breakdown -->
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase small">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        Aktivitas (7 Hari Terakhir)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 280px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & System Status -->
<div class="container-fluid mb-4 px-0">
    <div class="row g-3">
        <!-- Recent Activities -->
        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase small">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Aktivitas Terbaru
                        </h6>
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
                                                        <?php if (!empty($activity->photo) && file_exists(FCPATH . $activity->photo)): ?>
                                                            <img src="<?= base_url($activity->photo) ?>" alt="" class="me-2 rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="avatar-circle bg-primary text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                                <?= strtoupper(substr($activity->username, 0, 2)) ?>
                                                            </div>
                                                        <?php endif; ?>
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
                                                    <?= date('H:i:s', strtotime($activity->created_at)) ?>
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
        <div class="col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase small">
                        <i class="fas fa-server me-2 text-success"></i>
                        System Status
                    </h6>
                </div>
                <div class="card-body">
                    <!-- System Health Badge -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-success bg-gradient d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check text-white fa-2x"></i>
                        </div>
                        <h6 class="mt-3 mb-1">System Healthy</h6>
                        <small class="text-muted">All services operational</small>
                    </div>

                    <!-- Quick Stats Grid -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-success counter-simple" data-target="<?= $stats['posts']['total'] ?? 0 ?>">0</div>
                                <small class="text-muted d-block">Posts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-info counter-simple" data-target="<?= $stats['pages']['total'] ?? 0 ?>">0</div>
                                <small class="text-muted d-block">Pages</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-warning counter-simple" data-target="<?= $stats['galleries']['total'] ?? 0 ?>">0</div>
                                <small class="text-muted d-block">Galleries</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-primary counter-simple" data-target="<?= $stats['media']['total'] ?? 0 ?>">0</div>
                                <small class="text-muted d-block">Media</small>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Bars -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-3 small">Performance</h6>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Storage Usage</small>
                                <small class="text-muted"><?= formatBytes($stats['media']['total_size'] ?? 0) ?></small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <?php
                                $totalSize = $stats['media']['total_size'] ?? 0;
                                $maxSize = 1073741824; // 1GB
                                $percentage = min(($totalSize / $maxSize) * 100, 100);
                                ?>
                                <div class="progress-bar bg-warning" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Content Items</small>
                                <small class="text-muted"><?= ($stats['posts']['total'] + $stats['pages']['total'] + $stats['galleries']['total']) ?> items</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: 65%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Active Users</small>
                                <small class="text-muted"><?= $stats['users']['total'] ?? 0 ?> users</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts JavaScript -->
<script>
    jQuery(document).ready(function($) {
        // Chart.js Configuration
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
        Chart.defaults.color = '#6c757d';

        // Visitor Statistics Chart
        const visitorCtx = document.getElementById('visitorChart');
        if (visitorCtx) {
            new Chart(visitorCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($stats['visitor_chart']['labels'] ?? []) ?>,
                    datasets: [{
                        label: 'Pengunjung',
                        data: <?= json_encode($stats['visitor_chart']['data'] ?? []) ?>,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(54, 162, 235)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Content Distribution Chart
        const contentCtx = document.getElementById('contentChart');
        if (contentCtx) {
            const contentDist = <?= json_encode($stats['content_distribution'] ?? []) ?>;
            new Chart(contentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Posts', 'Pages', 'Galleries', 'Media'],
                    datasets: [{
                        data: [
                            contentDist.posts || 0,
                            contentDist.pages || 0,
                            contentDist.galleries || 0,
                            contentDist.media || 0
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
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Activity Breakdown Chart
        const activityCtx = document.getElementById('activityChart');
        if (activityCtx) {
            const activityData = <?= json_encode($stats['activity_breakdown'] ?? []) ?>;
            const labels = activityData.map(item => item.action.replace('_', ' '));
            const data = activityData.map(item => item.count);

            new Chart(activityCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Actions',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
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
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Animated Counter Function
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 16);
        }

        // Initialize counters (both animate and simple)
        document.querySelectorAll('.counter-animate, .counter-simple').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            if (!isNaN(target)) {
                animateCounter(counter, target);
            }
        });
    }); // End jQuery ready
</script>

<?= $this->endSection() ?>