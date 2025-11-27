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

<!-- Filter Toolbar -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-4 mb-2 mb-md-0">
        <div class="d-flex align-items-center">
            <div class="input-group input-group-sm me-2 flex-grow-1 flex-md-grow-0" style="min-width: 200px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="applyFilters()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <a href="<?= base_url('dashboard/posts/trash') ?>" class="btn btn-outline-danger btn-sm text-nowrap" title="Lihat Trash">
                <i class="fas fa-trash me-1"></i> Trash
            </a>
        </div>
    </div>
    <div class="col-12 col-md-8 text-md-end">
        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-start justify-content-md-end">
            <!-- Filters -->
            <select class="form-select form-select-sm flex-grow-1 flex-md-grow-0 w-auto" onchange="applyFilters()" id="filterCategory">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat->id ?>" <?= ($filterCategory ?? '') == $cat->id ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
                <?php endforeach; ?>
            </select>

            <select class="form-select form-select-sm flex-grow-1 flex-md-grow-0 w-auto" onchange="applyFilters()" id="filterAuthor">
                <option value="">All Authors</option>
                <?php foreach($users as $user): ?>
                    <option value="<?= $user->id ?>" <?= ($filterAuthor ?? '') == $user->id ? 'selected' : '' ?>><?= esc($user->fullname) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Sort By -->
            <select class="form-select form-select-sm flex-grow-1 flex-md-grow-0 w-auto" onchange="applyFilters()" id="sortBy">
                <option value="newest" <?= ($sortBy ?? 'newest') == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                <option value="oldest" <?= ($sortBy ?? 'newest') == 'oldest' ? 'selected' : '' ?>>Terlama</option>
                <option value="title_asc" <?= ($sortBy ?? 'newest') == 'title_asc' ? 'selected' : '' ?>>Judul A-Z</option>
                <option value="title_desc" <?= ($sortBy ?? 'newest') == 'title_desc' ? 'selected' : '' ?>>Judul Z-A</option>
                <option value="popular" <?= ($sortBy ?? 'newest') == 'popular' ? 'selected' : '' ?>>Terpopuler</option>
            </select>

            <!-- Items Per Page -->
            <select class="form-select form-select-sm flex-grow-0 w-auto" onchange="applyFilters()" id="perPage">
                <option value="10" <?= ($perPage ?? 25) == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= ($perPage ?? 25) == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($perPage ?? 25) == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($perPage ?? 25) == 100 ? 'selected' : '' ?>>100</option>
            </select>

            <!-- Bulk Actions -->
            <div class="btn-group btn-group-sm" id="bulkActionsGroup" style="display:none;">
                <button type="button" class="btn btn-danger" onclick="bulkAction('delete', 'Hapus item terpilih?')">
                    <i class="fas fa-trash"></i> Hapus (<span class="bulk-count">0</span>)
                </button>
            </div>

            <!-- Create Button -->
            <a href="<?= base_url('dashboard/posts/new') ?>" class="btn btn-primary btn-sm flex-grow-1 flex-md-grow-0">
                <i class="fas fa-plus"></i> Buat Baru
            </a>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const sortBy = document.getElementById('sortBy').value;
    const perPage = document.getElementById('perPage').value;
    const category = document.getElementById('filterCategory').value;
    const author = document.getElementById('filterAuthor').value;
    
    const currentUrl = new URL(window.location.href);
    
    if (search) currentUrl.searchParams.set('search', search);
    else currentUrl.searchParams.delete('search');
    
    if (sortBy) currentUrl.searchParams.set('sort', sortBy);
    
    if (category) currentUrl.searchParams.set('category', category);
    else currentUrl.searchParams.delete('category');
    
    if (author) currentUrl.searchParams.set('author', author);
    else currentUrl.searchParams.delete('author');
    
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to page 1
    
    window.location.href = currentUrl.toString();
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

// Bulk Actions Logic
let selectedIds = new Set();

function toggleBulkSelection(checkbox) {
    const id = checkbox.value;
    if (checkbox.checked) {
        selectedIds.add(id);
    } else {
        selectedIds.delete(id);
    }
    updateBulkActionsUI();
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        toggleBulkSelection(cb);
    });
}

function updateBulkActionsUI() {
    const bulkGroup = document.getElementById('bulkActionsGroup');
    const counts = document.querySelectorAll('.bulk-count');
    
    if (selectedIds.size > 0) {
        bulkGroup.style.display = 'inline-flex';
        counts.forEach(count => count.textContent = selectedIds.size);
    } else {
        bulkGroup.style.display = 'none';
    }
}

function bulkAction(action, confirmMsg) {
    if (selectedIds.size === 0) {
        alert('Pilih item terlebih dahulu');
        return;
    }
    
    if (!confirm(confirmMsg + ` (${selectedIds.size} item)`)) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('dashboard/posts/bulk-delete') ?>'; // Specific for posts
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '<?= csrf_token() ?>';
    csrf.value = '<?= csrf_hash() ?>';
    form.appendChild(csrf);
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
