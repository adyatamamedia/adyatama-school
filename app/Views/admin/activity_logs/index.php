<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<!-- Filters -->
<div class="card shadow-sm mb-4">
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
<div class="card shadow-sm mb-4">
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
                            // Helper function untuk badge color, icon, and label based on action
                            $badgeColor = getActivityBadge($log->action);
                            $badgeIcon = getActivityIcon($log->action);
                            $badgeLabel = ucfirst(str_replace('_', ' ', $log->action));
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
                                    <span class="badge bg-<?= $badgeColor ?>" style="font-size: 11px;">
                                        <i class="<?= $badgeIcon ?> me-1"></i>
                                        <?= $badgeLabel ?>
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


<?= $this->endSection() ?>