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

<div class="card shadow-sm mb-4">
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
                        <th width="150">Last Login</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $u) : ?>
                            <?php 
                                $currentUser = current_user();
                                $isCurrentUser = $currentUser->id == $u->id;
                                $isAdminUser = strtolower($u->role_name ?? '') === 'admin';
                                $isOperator = ($currentUser->role ?? '') === 'operator';
                                
                                // Logic: Operator cannot touch Admin. User cannot DELETE themselves (but can edit).
                                $operatorRestricted = ($isOperator && $isAdminUser);
                                
                                $canEdit = !$operatorRestricted;
                                $canDelete = !$isCurrentUser && !$operatorRestricted;
                            ?>
                            <tr>
                                <td>
                                    <?php if($canDelete): ?>
                                        <input type="checkbox" class="form-check-input bulk-checkbox" value="<?= $u->id ?>" onchange="toggleBulkSelection(this)">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <?= esc($u->username) ?>
                                        <?php if($isCurrentUser): ?>
                                            <span class="badge bg-primary text-white ms-1">You</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted"><?= esc($u->email) ?></small>
                                </td>
                                <td><?= esc($u->fullname) ?></td>
                                <td>
                                    <?php if($isAdminUser): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php elseif(strtolower($u->role_name ?? '') === 'operator'): ?>
                                        <span class="badge bg-warning text-dark">Operator</span>
                                    <?php elseif(strtolower($u->role_name ?? '') === 'guru'): ?>
                                        <span class="badge bg-info text-dark">Guru</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= esc($u->role_name ?? 'N/A') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($u->status == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $u->last_login ? date('d M Y', strtotime($u->last_login)) : '-' ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <?php if($canEdit): ?>
                                        <a href="<?= base_url('dashboard/users/edit/' . $u->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($canDelete): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="showDeleteModal('<?= base_url('dashboard/users/delete/' . $u->id) ?>', 'Hapus user \'<?= esc($u->fullname) ?>\'?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if(!$canEdit && !$canDelete): ?>
                                        <span class="badge bg-light text-muted" title="Restricted">
                                            <i class="fas fa-lock"></i> Protected
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <?= $search ? 'No users found matching your search.' : 'No users available.' ?>
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
            Menampilkan <?= count($users) ?> dari <?= $pager->getTotal() ?> user
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<?= $this->endSection() ?>
