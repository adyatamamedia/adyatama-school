<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?= base_url('dashboard/posts/new') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> New Post
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
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= esc($post->title) ?></div>
                                    <small class="text-muted">/<?= esc($post->slug) ?></small>
                                </td>
                                <td><?= esc($post->author_name) ?></td>
                                <td>
                                    <?php if($post->category_name): ?>
                                        <span class="badge bg-info text-dark"><?= esc($post->category_name) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($post->status == 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= $post->created_at ?></small>
                                </td>
                                <td>
                                    <a href="<?= base_url('dashboard/posts/edit/' . $post->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('dashboard/posts/delete/' . $post->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
