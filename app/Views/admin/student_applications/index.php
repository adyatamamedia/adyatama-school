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

<!-- Filter Component with Export Button -->
<div class="row mb-3 align-items-center">
    <div class="col-md-3">
        <!-- Search Box -->
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="searchInput" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
            <button class="btn btn-outline-secondary" type="button" onclick="applyFilters()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-9 text-end">
        <!-- Sort By -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="applyFilters()" id="sortBy">
            <?php foreach($sortOptions as $value => $label): ?>
                <option value="<?= $value ?>" <?= $sortBy == $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        
        <!-- Items Per Page -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="applyFilters()" id="perPage">
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
        </select>

        <!-- Filter Status -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="applyFilters()" id="filterStatus">
            <?php foreach($statusOptions as $value => $label): ?>
                <option value="<?= $value ?>" <?= $filterStatus == $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        
        <!-- Bulk Actions -->
        <div class="btn-group btn-group-sm me-2" id="bulkActionsGroup" style="display:none;">
            <?php foreach($bulkActions as $action): ?>
                <button type="button" class="btn btn-<?= $action['variant'] ?>" onclick="bulkAction('<?= $action['action'] ?>', '<?= $action['confirm'] ?>')">
                    <i class="fas fa-<?= $action['icon'] ?>"></i> <?= $action['label'] ?> (<span class="bulk-count">0</span>)
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Export Excel Button -->
        <a href="<?= base_url('dashboard/pendaftaran/export-excel?' . http_build_query(['search' => $search, 'filter_status' => $filterStatus])) ?>" 
           class="btn btn-success btn-sm me-2">
            <i class="fas fa-file-excel"></i> Excel
        </a>
    </div>
</div>

<script>
// Apply filters function
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const sortBy = document.getElementById('sortBy').value;
    const perPage = document.getElementById('perPage').value;
    const filterStatus = document.getElementById('filterStatus').value;
    const currentUrl = new URL(window.location.href);
    
    if (search) {
        currentUrl.searchParams.set('search', search);
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    currentUrl.searchParams.set('sort', sortBy);
    currentUrl.searchParams.set('per_page', perPage);
    
    if (filterStatus) {
        currentUrl.searchParams.set('filter_status', filterStatus);
    } else {
        currentUrl.searchParams.delete('filter_status');
    }
    
    currentUrl.searchParams.delete('page'); // Reset to page 1
    
    window.location.href = currentUrl.toString();
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

// Bulk selection management
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
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('dashboard/pendaftaran') ?>/bulk-' + action;
    
    // Add IDs
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

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" onchange="toggleSelectAll(this)">
                        </th>
                        <th width="50">No</th>
                        <th>Nama Lengkap</th>
                        <th width="120">NISN</th>
                        <th width="100">L/P</th>
                        <th>Nama Orang Tua</th>
                        <th width="130">No HP</th>
                        <th>Asal Sekolah</th>
                        <th width="100">Status</th>
                        <th width="100">Tanggal</th>
                        <th width="150" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applications)) : ?>
                        <?php 
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start = ($page - 1) * $perPage;
                        ?>
                        <?php foreach ($applications as $index => $app) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= $app['id'] ?>" onchange="toggleBulkSelection(this)">
                                </td>
                                <td><?= $start + $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold"><?= esc($app['nama_lengkap']) ?></div>
                                    <small class="text-muted">TTL: <?= esc($app['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($app['tanggal_lahir'])) ?></small>
                                </td>
                                <td><?= esc($app['nisn'] ?? '-') ?></td>
                                <td>
                                    <?php if($app['jenis_kelamin'] == 'L'): ?>
                                        <span class="badge bg-info">L</span>
                                    <?php else: ?>
                                        <span class="badge bg-pink">P</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($app['nama_ortu']) ?></td>
                                <td><?= esc($app['no_hp']) ?></td>
                                <td>
                                    <small><?= esc($app['asal_sekolah'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'review' => 'info',
                                        'accepted' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'review' => 'Review',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $statusColors[$app['status']] ?>">
                                        <?= $statusLabels[$app['status']] ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d M Y', strtotime($app['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- View Detail -->
                                        <a href="<?= base_url('dashboard/pendaftaran/view/' . $app['id']) ?>" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Toggle Status: Pending <-> Accepted -->
                                        <form method="post" action="<?= base_url('dashboard/pendaftaran/update-status/' . $app['id']) ?>" style="display:inline;">
                                            <?php if($app['status'] == 'pending'): ?>
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Terima Pendaftaran">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Kembalikan ke Pending">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            <?php endif; ?>
                                        </form>

                                        <!-- Export DOC -->
                                        <a href="<?= base_url('dashboard/pendaftaran/export-doc/' . $app['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Export ke DOC">
                                            <i class="fas fa-file-word"></i>
                                        </a>

                                        <!-- Delete -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="showDeleteModal('<?= base_url('dashboard/pendaftaran/delete/' . $app['id']) ?>', 'Hapus pendaftaran \'<?= esc($app['nama_lengkap']) ?>\'?')" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted">
                                <?= $search ? 'Tidak ada data yang sesuai dengan pencarian.' : 'Belum ada data pendaftaran.' ?>
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
            <?= $pager->links('default', 'default_full', ['sort' => $sortBy, 'per_page' => $perPage, 'search' => $search, 'filter_status' => $filterStatus]) ?>
        </nav>
        <div class="text-center text-muted small mb-3">
            Menampilkan <?= count($applications) ?> dari <?= $pager->getTotal() ?> pendaftaran
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<style>
.bg-pink {
    background-color: #e83e8c !important;
    color: white;
}
</style>

<?= $this->endSection() ?>
