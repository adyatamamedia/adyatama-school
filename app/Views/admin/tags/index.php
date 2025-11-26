<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter Component -->
<?= $this->include('admin/partials/datatable_filters') ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Tag Name</th>
                        <th width="200">Slug</th>
                        <th width="150">Usage Count</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tags)) : ?>
                        <?php foreach ($tags as $tag) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= esc($tag->slug) ?>" onchange="toggleBulkSelection(this)">
                                </td>
                                <td>
                                    <span class="badge bg-secondary fs-6"><?= esc($tag->name) ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= esc($tag->slug) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark"><?= $tag->count ?> post(s)</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="showDeleteModal('<?= base_url('dashboard/tags/delete/' . $tag->slug) ?>', 'Hapus tag \'<?= esc($tag->name) ?>\' dari SEMUA post?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <?= $search ? 'No tags found matching your search.' : 'No tags available.' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($pager->getPageCount() > 1): ?>
<div class="row">
    <div class="col-12">
        <nav aria-label="Page navigation">
            <?= $pager->links('default', 'default_full', ['sort' => $sortBy, 'per_page' => $perPage, 'search' => $search]) ?>
        </nav>
        <div class="text-center text-muted small mb-3">
            Menampilkan <?= count($tags) ?> dari <?= $pager->getTotal() ?> tag
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<?= $this->endSection() ?>
