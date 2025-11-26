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

<!-- Additional Filters -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label small">Filter by Category:</label>
                <select class="form-select form-select-sm" onchange="applyAdditionalFilters()" id="filterCategory">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= $filterCategory == $cat->id ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Filter by Author:</label>
                <select class="form-select form-select-sm" onchange="applyAdditionalFilters()" id="filterAuthor">
                    <option value="">All Authors</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user->id ?>" <?= $filterAuthor == $user->id ? 'selected' : '' ?>><?= esc($user->fullname) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
function applyAdditionalFilters() {
    const category = document.getElementById('filterCategory').value;
    const author = document.getElementById('filterAuthor').value;
    const currentUrl = new URL(window.location.href);
    
    if (category) {
        currentUrl.searchParams.set('category', category);
    } else {
        currentUrl.searchParams.delete('category');
    }
    
    if (author) {
        currentUrl.searchParams.set('author', author);
    } else {
        currentUrl.searchParams.delete('author');
    }
    
    currentUrl.searchParams.delete('page'); // Reset to page 1
    window.location.href = currentUrl.toString();
}
</script>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Title</th>
                        <th width="100">Type</th>
                        <th width="150">Author</th>
                        <th width="120">Category</th>
                        <th width="100">Status</th>
                        <th width="150">Date</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= $post->id ?>" onchange="toggleBulkSelection(this)">
                                </td>
                                <td>
                                    <div class="fw-bold"><?= esc($post->title) ?></div>
                                    <small class="text-muted">/<?= esc($post->slug) ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $post->post_type == 'article' ? 'bg-primary' : 'bg-warning text-dark' ?>">
                                        <?= ucfirst($post->post_type ?? 'article') ?>
                                    </span>
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
                                    <small class="text-muted"><?= date('d M Y', strtotime($post->created_at)) ?></small>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('dashboard/posts/edit/' . $post->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="showDeleteModal('<?= base_url('dashboard/posts/delete/' . $post->id) ?>', 'Hapus post \'<?= esc($post->title) ?>\'?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <?= $search ? 'No posts found matching your search.' : 'No posts available.' ?>
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
            <?= $pager->links('default', 'default_full', ['sort' => $sortBy, 'per_page' => $perPage, 'search' => $search, 'category' => $filterCategory, 'author' => $filterAuthor]) ?>
        </nav>
        <div class="text-center text-muted small mb-3">
            Menampilkan <?= count($posts) ?> dari <?= $pager->getTotal() ?> post
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<?= $this->endSection() ?>
