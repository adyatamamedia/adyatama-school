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
                        <th>Author</th>
                        <th>Comment</th>
                        <th>In Response To</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($comments)) : ?>
                        <?php foreach ($comments as $comment) : ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= esc($comment->author_name) ?></div>
                                    <small class="text-muted"><?= esc($comment->author_email) ?></small>
                                </td>
                                <td>
                                    <?= esc($comment->content) ?>
                                </td>
                                <td>
                                    <a href="#" class="text-decoration-none">
                                        <?= esc($comment->post_title) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if($comment->is_spam): ?>
                                        <span class="badge bg-danger">Spam</span>
                                    <?php elseif($comment->is_approved): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= $comment->created_at ?></small></td>
                                <td>
                                    <?php if(!$comment->is_approved && !$comment->is_spam): ?>
                                        <a href="<?= base_url('dashboard/comments/approve/' . $comment->id) ?>" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if(!$comment->is_spam): ?>
                                        <a href="<?= base_url('dashboard/comments/spam/' . $comment->id) ?>" class="btn btn-sm btn-warning" title="Mark as Spam">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('dashboard/comments/delete/' . $comment->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No comments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
