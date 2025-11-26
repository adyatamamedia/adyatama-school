<!-- Datatable Filters Component -->
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
        <?php if (isset($sortOptions) && !empty($sortOptions)): ?>
        <!-- Sort By -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="applyFilters()" id="sortBy">
            <?php foreach ($sortOptions as $value => $label): ?>
                <option value="<?= $value ?>" <?= ($sortBy ?? 'newest') == $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        
        <!-- Items Per Page -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="applyFilters()" id="perPage">
            <option value="10" <?= ($perPage ?? 25) == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($perPage ?? 25) == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($perPage ?? 25) == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= ($perPage ?? 25) == 100 ? 'selected' : '' ?>>100</option>
        </select>
        
        <?php if (isset($enableBulkActions) && $enableBulkActions): ?>
        <!-- Bulk Actions -->
        <div class="btn-group btn-group-sm me-2" id="bulkActionsGroup" style="display:none;">
            <?php if (isset($bulkActions) && !empty($bulkActions)): ?>
                <?php foreach ($bulkActions as $action): ?>
                    <button type="button" class="btn btn-<?= $action['variant'] ?? 'secondary' ?>" onclick="bulkAction('<?= $action['action'] ?>', '<?= $action['confirm'] ?? 'Yakin?' ?>')">
                        <i class="fas fa-<?= $action['icon'] ?>"></i> <?= $action['label'] ?> (<span class="bulk-count">0</span>)
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($createButton) && $createButton): ?>
        <!-- Create Button -->
        <?php if (isset($createButton['modal'])): ?>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $createButton['modal'] ?>">
                <i class="fas fa-plus"></i> <?= $createButton['label'] ?? 'Buat Baru' ?>
            </button>
        <?php else: ?>
            <a href="<?= $createButton['url'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?= $createButton['label'] ?? 'Buat Baru' ?>
            </a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Apply filters function
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const sortBy = document.getElementById('sortBy') ? document.getElementById('sortBy').value : '';
    const perPage = document.getElementById('perPage').value;
    const currentUrl = new URL(window.location.href);
    
    if (search) {
        currentUrl.searchParams.set('search', search);
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    if (sortBy) {
        currentUrl.searchParams.set('sort', sortBy);
    }
    
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

// Bulk selection management
<?php if (isset($enableBulkActions) && $enableBulkActions): ?>
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
    form.action = '<?= base_url(uri_string()) ?>/bulk-' + action;
    
    // Add CSRF token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '<?= csrf_token() ?>';
    csrf.value = '<?= csrf_hash() ?>';
    form.appendChild(csrf);
    
    // Add IDs
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    // Add action type if not delete
    if (action !== 'delete') {
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'bulk_action';
        actionInput.value = action;
        form.appendChild(actionInput);
    }
    
    document.body.appendChild(form);
    form.submit();
}
<?php endif; ?>
</script>
