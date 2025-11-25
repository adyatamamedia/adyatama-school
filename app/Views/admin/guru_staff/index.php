<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?= base_url('dashboard/guru-staff/new') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> Add New
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
                        <th style="width: 80px;">Photo</th>
                        <th>Name / NIP</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($staff_list)) : ?>
                        <?php foreach ($staff_list as $person) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php if($person->foto): ?>
                                        <img src="<?= base_url($person->foto) ?>" alt="Foto" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= esc($person->nama_lengkap) ?></div>
                                    <small class="text-muted"><?= esc($person->nip ?? '-') ?></small>
                                </td>
                                <td>
                                    <div><?= esc($person->jabatan) ?></div>
                                    <small class="text-muted"><?= esc($person->bidang) ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $person->status == 'guru' ? 'bg-primary' : 'bg-info' ?>">
                                        <?= ucfirst($person->status) ?>
                                    </span>
                                    <?php if(!$person->is_active): ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('dashboard/guru-staff/edit/' . $person->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('dashboard/guru-staff/delete/' . $person->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
