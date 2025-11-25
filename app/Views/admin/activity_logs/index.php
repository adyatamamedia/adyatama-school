<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="mb-3"></div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Recent Activities (Last 100)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>IP Address</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)) : ?>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td>
                                    <?php if($log->username): ?>
                                        <span class="fw-bold"><?= esc($log->username) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">System/Guest</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log->action) ?></td>
                                <td>
                                    <?php if($log->subject_type): ?>
                                        <span class="badge bg-light text-dark border"><?= esc($log->subject_type) ?></span>
                                        <small class="text-muted">#<?= esc($log->subject_id) ?></small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log->ip_address) ?></td>
                                <td><?= $log->created_at ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
