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
        <?php if (isset($extraFilters)) echo $extraFilters; ?>

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

        <!-- Trash Button (for modules with trash support) -->
        <?php if (isset($enableTrash) && $enableTrash): ?>
            <?php
            $currentPath = current_url() ?: uri_string();
            $trashPath = '';
            if (strpos($currentPath, 'posts') !== false) $trashPath = 'dashboard/posts/trash';
            elseif (strpos($currentPath, 'media') !== false) $trashPath = 'dashboard/media/trash';
            elseif (strpos($currentPath, 'pages') !== false) $trashPath = 'dashboard/pages/trash';
            elseif (strpos($currentPath, 'galleries') !== false) $trashPath = 'dashboard/galleries/trash';
            elseif (strpos($currentPath, 'guru-staff') !== false) $trashPath = 'dashboard/guru-staff/trash';
            elseif (strpos($currentPath, 'student-applications') !== false) $trashPath = 'dashboard/student-applications/trash';
            elseif (strpos($currentPath, 'users') !== false) $trashPath = 'dashboard/users/trash';
            ?>
            <?php if ($trashPath): ?>
                <a href="<?= base_url($trashPath) ?>" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-trash"></i> Trash
                </a>
            <?php endif; ?>
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

        // Custom Filters
        const extraFilter = document.getElementById('filterExtracurricular'); // Hardcoded for now or generic?

        const currentUrl = new URL(window.location.href);

        if (search) {
            currentUrl.searchParams.set('search', search);
        } else {
            currentUrl.searchParams.delete('search');
        }

        if (sortBy) {
            currentUrl.searchParams.set('sort', sortBy);
        }

        if (extraFilter) {
            if (extraFilter.value) {
                currentUrl.searchParams.set('extracurricular', extraFilter.value);
            } else {
                currentUrl.searchParams.delete('extracurricular');
            }
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
                showBulkActionModal('Pilih item terlebih dahulu', false);
                return;
            }

            showBulkActionModal(confirmMsg + ` (${selectedIds.size} item)`, action);
        }

        function submitBulkAction(action) {
            if (!action) return;

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
                <button type="button" id="btnConfirmBulk" class="btn btn-primary" onclick="submitBulkAction(currentBulkAction)">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentBulkAction = null;

function showBulkActionModal(message, action = null) {
    if (action === false) {
        // Just show error message without action buttons
        document.getElementById('bulkActionMessage').textContent = message;
        document.getElementById('btnConfirmBulk').style.display = 'none';
        
        const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
        modal.show();
        
        // Auto close after 2 seconds
        setTimeout(() => {
            modal.hide();
        }, 2000);
        
        return;
    }
    
    currentBulkAction = action;
    document.getElementById('bulkActionMessage').textContent = message;
    document.getElementById('btnConfirmBulk').style.display = 'block';
    
    // Update button style based on action
    const btnConfirm = document.getElementById('btnConfirmBulk');
    if (action === 'delete' || action === 'force-delete') {
        btnConfirm.className = 'btn btn-danger';
        btnConfirm.innerHTML = '<i class="fas fa-trash me-1"></i> Ya, Hapus';
    } else if (action === 'restore') {
        btnConfirm.className = 'btn btn-success';
        btnConfirm.innerHTML = '<i class="fas fa-trash-restore me-1"></i> Ya, Kembalikan';
    } else {
        btnConfirm.className = 'btn btn-primary';
        btnConfirm.innerHTML = 'Ya, Lanjutkan';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}
</script>