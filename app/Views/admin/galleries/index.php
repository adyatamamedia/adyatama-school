<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?= base_url('dashboard/galleries/new') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> New Gallery
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
                        <th>Title</th>
                        <th>Extracurricular</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th style="width: 250px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($galleries)) : ?>
                        <?php foreach ($galleries as $gallery) : ?>
                            <tr>
                                <td><?= esc($gallery->title) ?></td>
                                <td><?= esc($gallery->ekskul_name ?? '-') ?></td>
                                <td>
                                    <?php if($gallery->status == 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $gallery->created_at ?></td>
                                <td>
                                    <a href="<?= base_url('dashboard/galleries/items/' . $gallery->id) ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-images"></i> Photos
                                    </a>
                                    <a href="<?= base_url('dashboard/galleries/edit/' . $gallery->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('dashboard/galleries/delete/' . $gallery->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No galleries found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
