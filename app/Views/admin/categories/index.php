<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?= base_url('dashboard/categories/new') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> New Category
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
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <td><?= esc($category->name) ?></td>
                                <td><span class="badge bg-light text-dark"><?= esc($category->slug) ?></span></td>
                                <td><?= esc($category->description) ?></td>
                                <td>
                                    <a href="<?= base_url('dashboard/categories/edit/' . $category->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('dashboard/categories/delete/' . $category->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This might affect posts linked to this category.')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
