<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="mb-3"></div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Subscribed At</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($subscribers)) : ?>
                        <?php foreach ($subscribers as $sub) : ?>
                            <tr>
                                <td><?= esc($sub->email) ?></td>
                                <td><?= esc($sub->name ?? '-') ?></td>
                                <td>
                                    <?php if($sub->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $sub->subscribed_at ?></td>
                                <td>
                                    <a href="<?= base_url('dashboard/subscribers/toggle/' . $sub->id) ?>" class="btn btn-sm btn-info text-white" title="Toggle Status">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                    <a href="<?= base_url('dashboard/subscribers/delete/' . $sub->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subscriber?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No subscribers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
