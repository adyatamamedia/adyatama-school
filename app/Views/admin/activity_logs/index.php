<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Activity Log</h1>
    <div>
        <small class="text-muted">Total: <?= $pager->getTotal() ?> aktivitas</small>
    </div>
</div>

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-header bg-light">
        <strong><i class="fas fa-filter me-2"></i>Filter & Search</strong>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('dashboard/activity-logs') ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Username, action, IP..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">User</label>
                    <select name="user" class="form-select form-select-sm">
                        <option value="">-- Semua User --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id ?>" <?= ($filterUser == $user->id) ? 'selected' : '' ?>>
                                <?= esc($user->username) ?> (<?= esc($user->fullname) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">-- Semua Action --</option>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?= esc($action->action) ?>" <?= ($filterAction == $action->action) ? 'selected' : '' ?>>
                                <?= esc($action->action) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Date</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="<?= esc($filterDate ?? '') ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="<?= base_url('dashboard/activity-logs') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-redo me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Activity Log Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
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
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $index => $log): ?>
                            <?php
                            // Helper function untuk badge color based on action
                            $badgeConfig = getActivityBadge($log->action);
                            ?>
                            <tr>
                                <td class="text-muted"><?= ($index + 1) + ($pager->getCurrentPage() - 1) * 50 ?></td>
                                <td>
                                    <?php if ($log->username): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <?= strtoupper(substr($log->username, 0, 2)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold small"><?= esc($log->username) ?></div>
                                                <?php if ($log->fullname): ?>
                                                    <div class="text-muted" style="font-size: 11px;"><?= esc($log->fullname) ?></div>
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
                                    <span class="badge <?= $badgeConfig['badge'] ?>" style="font-size: 11px;">
                                        <i class="<?= $badgeConfig['icon'] ?> me-1"></i>
                                        <?= $badgeConfig['label'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <?= getActivityDescription($log) ?>
                                    </div>
                                    <?php if ($log->subject_type && $log->subject_id): ?>
                                        <div class="mt-1">
                                            <span class="badge bg-light text-dark border" style="font-size: 10px;">
                                                <?= esc($log->subject_type) ?> #<?= esc($log->subject_id) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-network-wired me-1"></i><?= esc($log->ip_address) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        <?= date('d M Y', strtotime($log->created_at)) ?>
                                    </div>
                                    <div style="font-size: 11px;" class="text-muted">
                                        <?= date('H:i:s', strtotime($log->created_at)) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                Tidak ada aktivitas yang ditemukan
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager->getPageCount() > 1): ?>
            <nav class="mt-3">
                <?= $pager->links('default', 'default_full') ?>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * Helper function to get badge configuration based on action
 */
function getActivityBadge($action)
{
    $badges = [
        // Auth
        'login' => ['badge' => 'bg-success', 'icon' => 'fas fa-sign-in-alt', 'label' => 'Login'],
        'logout' => ['badge' => 'bg-secondary', 'icon' => 'fas fa-sign-out-alt', 'label' => 'Logout'],
        
        // Post
        'create_post' => ['badge' => 'bg-primary', 'icon' => 'fas fa-plus-circle', 'label' => 'Create Post'],
        'update_post' => ['badge' => 'bg-info', 'icon' => 'fas fa-edit', 'label' => 'Update Post'],
        'delete_post' => ['badge' => 'bg-danger', 'icon' => 'fas fa-trash', 'label' => 'Delete Post'],
        'publish_post' => ['badge' => 'bg-success', 'icon' => 'fas fa-paper-plane', 'label' => 'Publish Post'],
        'draft_post' => ['badge' => 'bg-warning', 'icon' => 'fas fa-file', 'label' => 'Draft Post'],
        
        // Page
        'create_page' => ['badge' => 'bg-primary', 'icon' => 'fas fa-plus-circle', 'label' => 'Create Page'],
        'update_page' => ['badge' => 'bg-info', 'icon' => 'fas fa-edit', 'label' => 'Update Page'],
        'delete_page' => ['badge' => 'bg-danger', 'icon' => 'fas fa-trash', 'label' => 'Delete Page'],
        
        // Category
        'create_category' => ['badge' => 'bg-primary', 'icon' => 'fas fa-plus-circle', 'label' => 'Create Category'],
        'update_category' => ['badge' => 'bg-info', 'icon' => 'fas fa-edit', 'label' => 'Update Category'],
        'delete_category' => ['badge' => 'bg-danger', 'icon' => 'fas fa-trash', 'label' => 'Delete Category'],
        
        // User
        'create_user' => ['badge' => 'bg-primary', 'icon' => 'fas fa-user-plus', 'label' => 'Create User'],
        'update_user' => ['badge' => 'bg-info', 'icon' => 'fas fa-user-edit', 'label' => 'Update User'],
        'delete_user' => ['badge' => 'bg-danger', 'icon' => 'fas fa-user-times', 'label' => 'Delete User'],
        
        // Media
        'upload_media' => ['badge' => 'bg-primary', 'icon' => 'fas fa-cloud-upload-alt', 'label' => 'Upload Media'],
        'delete_media' => ['badge' => 'bg-danger', 'icon' => 'fas fa-trash', 'label' => 'Delete Media'],
        'update_media' => ['badge' => 'bg-info', 'icon' => 'fas fa-edit', 'label' => 'Update Media'],
        
        // Gallery
        'create_gallery' => ['badge' => 'bg-primary', 'icon' => 'fas fa-images', 'label' => 'Create Gallery'],
        'update_gallery' => ['badge' => 'bg-info', 'icon' => 'fas fa-edit', 'label' => 'Update Gallery'],
        'delete_gallery' => ['badge' => 'bg-danger', 'icon' => 'fas fa-trash', 'label' => 'Delete Gallery'],
        
        // Settings
        'update_settings' => ['badge' => 'bg-warning text-dark', 'icon' => 'fas fa-cog', 'label' => 'Update Settings'],
        
        // Other
        'student_application.created' => ['badge' => 'bg-info', 'icon' => 'fas fa-user-graduate', 'label' => 'New Application'],
    ];

    return $badges[$action] ?? ['badge' => 'bg-secondary', 'icon' => 'fas fa-circle', 'label' => ucfirst(str_replace('_', ' ', $action))];
}

/**
 * Helper function to get human-readable description
 */
function getActivityDescription($log)
{
    $descriptions = [
        'login' => 'Masuk ke sistem',
        'logout' => 'Keluar dari sistem',
        'create_post' => 'Membuat post baru',
        'update_post' => 'Mengupdate post',
        'delete_post' => 'Menghapus post',
        'publish_post' => 'Mempublish post',
        'draft_post' => 'Mengubah post ke draft',
        'create_page' => 'Membuat halaman baru',
        'update_page' => 'Mengupdate halaman',
        'delete_page' => 'Menghapus halaman',
        'create_category' => 'Membuat kategori baru',
        'update_category' => 'Mengupdate kategori',
        'delete_category' => 'Menghapus kategori',
        'create_user' => 'Membuat user baru',
        'update_user' => 'Mengupdate data user',
        'delete_user' => 'Menghapus user',
        'upload_media' => 'Upload media baru',
        'delete_media' => 'Menghapus media',
        'update_media' => 'Mengupdate media',
        'create_gallery' => 'Membuat galeri baru',
        'update_gallery' => 'Mengupdate galeri',
        'delete_gallery' => 'Menghapus galeri',
        'update_settings' => 'Mengupdate pengaturan website',
        'student_application.created' => 'Pendaftaran siswa baru',
    ];

    $description = $descriptions[$log->action] ?? ucfirst(str_replace('_', ' ', $log->action));
    
    // Add meta info if available
    if ($log->meta) {
        $meta = json_decode($log->meta, true);
        if ($meta && isset($meta['nama_lengkap'])) {
            $description .= ': <strong>' . esc($meta['nama_lengkap']) . '</strong>';
        }
    }
    
    return $description;
}
?>

<?= $this->endSection() ?>
