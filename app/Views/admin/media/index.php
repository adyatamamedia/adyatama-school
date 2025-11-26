<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<style>
.btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}
</style>

<div class="row mb-3">
    <div class="col-md-3">
        <!-- Search Box -->
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="searchMedia" placeholder="Cari media...">
            <button class="btn btn-outline-secondary" type="button" onclick="searchMedia()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-9 text-end">
        <!-- Sort By -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="updateFilters()" id="sortBy">
            <option value="newest" <?= $sortBy == 'newest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $sortBy == 'oldest' ? 'selected' : '' ?>>Terlama</option>
            <option value="name_asc" <?= $sortBy == 'name_asc' ? 'selected' : '' ?>>Nama A-Z</option>
            <option value="name_desc" <?= $sortBy == 'name_desc' ? 'selected' : '' ?>>Nama Z-A</option>
            <option value="size_asc" <?= $sortBy == 'size_asc' ? 'selected' : '' ?>>Ukuran Terkecil</option>
            <option value="size_desc" <?= $sortBy == 'size_desc' ? 'selected' : '' ?>>Ukuran Terbesar</option>
        </select>
        
        <!-- Items Per Page -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="updateFilters()" id="perPage">
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
        </select>
        
        <!-- View Toggle -->
        <div class="btn-group btn-group-sm me-2" role="group" aria-label="View toggle">
            <button type="button" class="btn btn-outline-secondary" id="gridViewBtn" onclick="setView('grid')" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary" id="listViewBtn" onclick="setView('list')" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>
        
        <button type="button" class="btn btn-danger btn-sm me-2" id="bulkDeleteBtn" style="display:none;" onclick="bulkDelete()">
            <i class="fas fa-trash"></i> Hapus (<span id="selectedCount">0</span>)
        </button>
        <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Grid View -->
<div class="row row-cols-2 row-cols-md-6 g-3" id="mediaGridView">
    <?php if (!empty($media)) : ?>
        <?php foreach ($media as $item) : ?>
            <div class="col media-item" data-caption="<?= strtolower(esc($item->caption)) ?>">
                <div class="card h-100 shadow-sm">
                    <div class="ratio ratio-1x1 bg-light position-relative group-action">
                        <?php if($item->type == 'image'): ?>
                            <img src="<?= base_url($item->path) ?>" class="card-img-top object-fit-cover h-100" alt="<?= esc($item->caption) ?>">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="fas fa-file fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Checkbox for Selection (Top Left with bigger hitbox) -->
                        <div class="position-absolute top-0 start-0 p-2" style="z-index: 10;">
                            <input type="checkbox" class="form-check-input media-checkbox" value="<?= $item->id ?>" onchange="updateBulkDeleteButton()" style="width: 20px; height: 20px; cursor: pointer;">
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <p class="card-text small text-truncate mb-1" title="<?= esc($item->caption) ?>">
                            <?= esc($item->caption) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted" style="font-size: 0.7rem;"><?= round($item->filesize / 1024) ?> KB</small>
                            <div>
                                <button class="btn btn-sm btn-link text-primary p-0 me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $item->id ?>" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <a href="<?= base_url('dashboard/media/delete/' . $item->id) ?>" class="text-danger small" onclick="return confirm('Delete?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-light text-center">No media files found.</div>
        </div>
    <?php endif; ?>
</div>

<!-- List View -->
<div class="table-responsive d-none" id="mediaListView">
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="40">
                    <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                </th>
                <th width="80">Preview</th>
                <th>Caption</th>
                <th width="100">Size</th>
                <th width="150">Date</th>
                <th width="100" class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($media)) : ?>
                <?php foreach ($media as $item) : ?>
                    <tr class="media-item-list" data-caption="<?= strtolower(esc($item->caption)) ?>">
                        <td>
                            <input type="checkbox" class="form-check-input media-checkbox" value="<?= $item->id ?>" onchange="updateBulkDeleteButton()">
                        </td>
                        <td>
                            <?php if($item->type == 'image'): ?>
                                <img src="<?= base_url($item->path) ?>" alt="<?= esc($item->caption) ?>" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-file fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= esc($item->caption) ?></strong>
                            <br>
                            <small class="text-muted"><?= esc($item->path) ?></small>
                        </td>
                        <td><?= round($item->filesize / 1024) ?> KB</td>
                        <td>
                            <small class="text-muted"><?= date('d M Y', strtotime($item->created_at)) ?></small>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $item->id ?>" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="<?= base_url('dashboard/media/delete/' . $item->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">No media files found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($pager->getPageCount() > 1): ?>
<div class="row mt-4">
    <div class="col-12">
        <nav aria-label="Page navigation">
            <?= $pager->links('default', 'default_full', ['sort' => $sortBy, 'per_page' => $perPage]) ?>
        </nav>
        <div class="text-center text-muted small">
            Menampilkan <?= count($media) ?> dari <?= $pager->getTotal() ?> media
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Edit Caption Modals (Shared for both views) -->
<?php if (!empty($media)) : ?>
    <?php foreach ($media as $item) : ?>
        <div class="modal fade" id="editModal<?= $item->id ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form action="<?= base_url('dashboard/media/update/' . $item->id) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-header">
                            <h6 class="modal-title">Edit Caption</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label class="form-label small">Caption</label>
                                <input type="text" name="caption" class="form-control form-control-sm" value="<?= esc($item->caption) ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('dashboard/media/upload-multiple') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title">Upload Media</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label for="files" class="form-label">Select Images (Multiple)</label>
                <input class="form-control" type="file" id="files" name="files[]" accept="image/*" multiple required>
                <div class="form-text">Max 10MB per file. You can select multiple files.</div>
            </div>
            <div id="filePreview" class="row row-cols-3 g-2 mb-3"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
// Update filters (sort and per page)
function updateFilters() {
    const sortBy = document.getElementById('sortBy').value;
    const perPage = document.getElementById('perPage').value;
    const currentUrl = new URL(window.location.href);
    
    currentUrl.searchParams.set('sort', sortBy);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to page 1
    
    window.location.href = currentUrl.toString();
}

// View Toggle
let currentView = localStorage.getItem('mediaView') || 'grid';

document.addEventListener('DOMContentLoaded', function() {
    setView(currentView, false);
});

function setView(view, savePreference = true) {
    currentView = view;
    
    const gridView = document.getElementById('mediaGridView');
    const listView = document.getElementById('mediaListView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    
    if (view === 'grid') {
        gridView.classList.remove('d-none');
        listView.classList.add('d-none');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    } else {
        gridView.classList.add('d-none');
        listView.classList.remove('d-none');
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
    }
    
    if (savePreference) {
        localStorage.setItem('mediaView', view);
    }
}

// Toggle select all (for list view)
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.media-checkbox');
    
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
    });
    
    updateBulkDeleteButton();
}

// Preview selected files
document.getElementById('files').addEventListener('change', function(e) {
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="card">
                    <img src="${event.target.result}" class="card-img-top" style="height: 80px; object-fit: cover;">
                    <div class="card-body p-1 text-center">
                        <small class="text-truncate d-block">${file.name}</small>
                    </div>
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});

// Search functionality (works for both views)
function searchMedia() {
    const searchTerm = document.getElementById('searchMedia').value.toLowerCase();
    
    // Grid view items
    const gridItems = document.querySelectorAll('.media-item');
    gridItems.forEach(item => {
        const caption = item.getAttribute('data-caption');
        if (caption.includes(searchTerm)) {
            item.classList.remove('d-none');
        } else {
            item.classList.add('d-none');
        }
    });
    
    // List view items
    const listItems = document.querySelectorAll('.media-item-list');
    listItems.forEach(item => {
        const caption = item.getAttribute('data-caption');
        if (caption.includes(searchTerm)) {
            item.classList.remove('d-none');
        } else {
            item.classList.add('d-none');
        }
    });
}

// Search on Enter key
document.getElementById('searchMedia').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        searchMedia();
    }
});

// Update bulk delete button visibility
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.media-checkbox:checked');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    const count = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkBtn.style.display = 'inline-block';
        count.textContent = checkboxes.length;
    } else {
        bulkBtn.style.display = 'none';
    }
}

// Bulk delete function
function bulkDelete() {
    const checkboxes = document.querySelectorAll('.media-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Pilih media yang ingin dihapus');
        return;
    }
    
    if (!confirm(`Hapus ${ids.length} media terpilih?`)) {
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('dashboard/media/bulk-delete') ?>';
    
    // Add CSRF token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '<?= csrf_token() ?>';
    csrf.value = '<?= csrf_hash() ?>';
    form.appendChild(csrf);
    
    // Add IDs
    ids.forEach(id => {
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
<?= $this->endSection() ?>
