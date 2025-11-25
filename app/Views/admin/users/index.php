<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?= base_url('dashboard/users/new') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> Create User
    </a>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $u) : ?>
                            <tr>
                                <td><?= esc($u->username) ?></td>
                                <td><?= esc($u->fullname) ?></td>
                                <td><span class="badge bg-info text-dark"><?= esc($u->role_name) ?></span></td>
                                <td>
                                    <?php if($u->status == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $u->last_login ?></td>
                                <td>
                                    <a href="<?= base_url('dashboard/users/edit/' . $u->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php if(current_user()->id != $u->id): ?>
                                    <a href="<?= base_url('dashboard/users/delete/' . $u->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
