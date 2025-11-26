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
                        <th>Name</th>
                        <th width="200">Slug</th>
                        <th>Description</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= $category->id ?>" onchange="toggleBulkSelection(this)">
                                </td>
                                <td>
                                    <div class="fw-bold"><?= esc($category->name) ?></div>
                                </td>
                                <td>
                                    <small class="text-muted"><?= esc($category->slug) ?></small>
                                </td>
                                <td>
                                    <small><?= esc($category->description) ?></small>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editCategory(<?= $category->id ?>, '<?= esc($category->name, 'js') ?>', '<?= esc($category->slug, 'js') ?>', '<?= esc($category->description ?? '', 'js') ?>')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="showDeleteModal('<?= base_url('dashboard/categories/delete/' . $category->id) ?>', 'Hapus kategori \'<?= esc($category->name) ?>\'? Post yang terkait akan terpengaruh.')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <?= $search ? 'No categories found matching your search.' : 'No categories available.' ?>
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
            Menampilkan <?= count($categories) ?> dari <?= $pager->getTotal() ?> kategori
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('dashboard/categories/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Buat Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug (opsional)</label>
                        <input type="text" name="slug" class="form-control">
                        <small class="text-muted">Kosongkan untuk auto-generate dari nama</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug (opsional)</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control">
                        <small class="text-muted">Kosongkan untuk auto-generate dari nama</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<script>
// Populate edit modal with data
function editCategory(id, name, slug, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_slug').value = slug;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('editCategoryForm').action = '<?= base_url('dashboard/categories/update/') ?>' + id;
    
    const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>
