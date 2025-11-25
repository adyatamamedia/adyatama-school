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
                        <th>Tag Name</th>
                        <th>Slug</th>
                        <th>Usage Count</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tags)) : ?>
                        <?php foreach ($tags as $tag) : ?>
                            <tr>
                                <td><span class="badge bg-secondary fs-6"><?= esc($tag->name) ?></span></td>
                                <td><?= esc($tag->slug) ?></td>
                                <td><?= $tag->count ?> Posts</td>
                                <td>
                                    <a href="<?= base_url('dashboard/tags/delete/' . $tag->slug) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tag from ALL posts?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">No tags found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
