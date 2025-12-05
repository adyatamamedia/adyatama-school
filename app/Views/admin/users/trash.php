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
    <div class="col-md-3">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="searchInput" placeholder="Cari di trash..." value="<?= esc($search ?? '') ?>">
            <button class="btn btn-outline-secondary" type="button" onclick="applyFilters()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-9 text-end">
        <div class="d-inline-flex align-items-center gap-2 justify-content-end">
            
            <!-- Items Per Page -->
            <select class="form-select form-select-sm" style="width: auto;" onchange="applyFilters()" id="perPage">
                <option value="10" <?= ($perPage ?? 25) == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= ($perPage ?? 25) == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($perPage ?? 25) == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($perPage ?? 25) == 100 ? 'selected' : '' ?>>100</option>
            </select>

            <!-- Bulk Actions -->
            <div class="btn-group btn-group-sm" id="bulkActionsGroup" style="display:none;">
                <button type="button" class="btn btn-success" onclick="confirmBulkAction('restore', 'Restore pengguna terpilih?')">
                    <i class="fas fa-trash-restore"></i> Restore (<span class="bulk-count">0</span>)
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmBulkAction('force-delete', 'Hapus PERMANEN pengguna terpilih?')">
                    <i class="fas fa-ban"></i> Delete Forever (<span class="bulk-count">0</span>)
                </button>
            </div>

            <!-- Back Button -->
            <a href="<?= base_url('dashboard/users') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Users
            </a>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const perPage = document.getElementById('perPage').value;
    
    const currentUrl = new URL(window.location.href);
    
    if (search) currentUrl.searchParams.set('search', search);
    else currentUrl.searchParams.delete('search');
    
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

</script>

<div class="card shadow-sm mb-4 border-danger border-start border-3">
    <div class="card-header bg-light text-danger">
        <i class="fas fa-trash me-2"></i> <strong>Trash (Deleted Users)</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th width="120">Role</th>
                        <th width="100">Status</th>
                        <th width="150">Deleted At</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= $user->id ?>" onchange="toggleBulkSelection(this)">
                                </td>
                                <td>
                                    <div class="fw-bold text-decoration-line-through text-muted"><?= esc($user->username) ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-decoration-line-through text-muted"><?= esc($user->fullname) ?></div>
                                </td>
                                <td>
                                    <span class="badge text-decoration-line-through bg-secondary">
                                        <?= ucfirst(esc($user->role_name ?? 'user')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-decoration-line-through bg-secondary">
                                        <?= ucfirst(esc($user->status ?? 'inactive')) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-danger"><?= date('d M Y H:i', strtotime($user->deleted_at)) ?></small>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-success" title="Restore" onclick="showRestoreModal('<?= base_url('dashboard/users/restore/' . $user->id) ?>', '<?= esc($user->fullname, 'js') ?>')">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Trash is empty.
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
            <?= $pager->links('default', 'default_full', ['per_page' => $perPage, 'search' => $search]) ?>
        </nav>
        <div class="text-center text-muted small mb-3">
            Menampilkan <?= count($users) ?> dari <?= $pager->getTotal() ?> pengguna di trash
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Restore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengembalikan pengguna <strong id="restoreItemTitle"></strong> dari trash?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="btnConfirmRestore" class="btn btn-success">
                    <i class="fas fa-trash-restore me-1"></i> Ya, Restore
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Aksi Masal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnConfirmBulk" class="btn btn-primary" onclick="submitBulkAction()">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showRestoreModal(url, title) {
    document.getElementById('restoreItemTitle').textContent = title;
    document.getElementById('btnConfirmRestore').href = url;
    new bootstrap.Modal(document.getElementById('restoreModal')).show();
}

// Bulk Action Modal Logic
let currentBulkAction = null;

function confirmBulkAction(action, message) {
    if (selectedIds.size === 0) {
        showBulkActionModal('Pilih item terlebih dahulu', false);
        return;
    }
    
    showBulkActionModal(message + ` (${selectedIds.size} item)`, action);
}

function submitBulkAction(action) {
    if (!action) action = currentBulkAction;
    if (!action) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('dashboard/users/bulk-') ?>' + action; 
    
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

<?= $this->endSection() ?>
