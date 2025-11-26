<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create User</h1>
    <a href="<?= base_url('dashboard/users') ?>" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('dashboard/users/create') ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="row">
        <!-- Left Column: Main Form -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <strong><i class="fas fa-user-plus me-2"></i>User Information</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?= old('fullname') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        <small class="text-muted">Unique username for login</small>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" placeholder="user@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings -->
        <div class="col-md-4">
            <!-- Role & Status Card -->
            <div class="card shadow mb-3">
                <div class="card-header bg-light">
                    <strong>Settings</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <?php foreach($roles as $role): ?>
                                <option value="<?= $role->id ?>" <?= old('role_id') == $role->id ? 'selected' : '' ?>>
                                    <?= ucfirst($role->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= old('status') == 'active' ? 'selected' : 'selected' ?>>Active</option>
                            <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Create User
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
