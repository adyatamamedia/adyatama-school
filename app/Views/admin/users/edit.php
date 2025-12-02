<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<!-- <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
    <a href="<?= base_url('dashboard/users') ?>" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div> -->

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

<form action="<?= base_url('dashboard/users/update/' . $user->id) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Left Column: Main Form -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <strong><i class="fas fa-user-edit me-2"></i>User Information</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?= old('fullname', $user->fullname) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user->username) ?>" required>
                        <small class="text-muted">Unique username for login</small>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user->email) ?>" placeholder="user@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Leave blank if you don't want to change password</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings -->
        <div class="col-md-4">
            <!-- Avatar Upload Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-image me-2"></i>Profile Photo</strong>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar Preview -->
                    <div class="mb-3">
                        <div class="avatar-preview mx-auto" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 3px solid #e9ecef; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <?php if (!empty($user->photo) && file_exists(FCPATH . $user->photo)): ?>
                                <img id="avatarPreview" src="<?= base_url($user->photo) ?>" alt="Current Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                <div id="avatarPlaceholder" style="font-size: 4rem; color: #dee2e6; display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php else: ?>
                                <img id="avatarPreview" src="" alt="Avatar Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <div id="avatarPlaceholder" style="font-size: 4rem; color: #dee2e6;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Current Photo Info -->
                    <?php if (!empty($user->photo)): ?>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Current photo uploaded
                            </small>
                        </div>
                    <?php endif; ?>

                    <!-- File Input -->
                    <div class="mb-2">
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewAvatar(event)">
                        <small class="text-muted d-block mt-1">Max: 2MB (JPG, PNG) - Leave blank to keep current</small>
                    </div>

                    <!-- Delete Photo Option -->
                    <?php if (!empty($user->photo)): ?>
                        <div class="mt-2" id="deletePhotoContainer">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" id="btnDeletePhoto" onclick="deletePhoto(<?= $user->id ?>)">
                                <i class="fas fa-trash me-1"></i>Delete Current Photo
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Role & Status Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Settings</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->id ?>" <?= old('role_id', $user->role_id) == $role->id ? 'selected' : '' ?>>
                                    <?= ucfirst($role->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= old('status', $user->status) == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status', $user->status) == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include Delete Photo Modal -->
<?= $this->include('admin/partials/delete_photo_modal') ?>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    let deletePhotoModal;
    let userIdToDelete;

    document.addEventListener('DOMContentLoaded', function() {
        deletePhotoModal = new bootstrap.Modal(document.getElementById('deletePhotoModal'));
        
        document.getElementById('confirmDeletePhotoBtn').addEventListener('click', function() {
            performDeletePhoto();
        });
    });

    function deletePhoto(userId) {
        userIdToDelete = userId;
        deletePhotoModal.show();
    }

    function performDeletePhoto() {
        const btn = document.getElementById('confirmDeletePhotoBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';

        fetch(`<?= base_url('dashboard/users/delete-photo') ?>/${userIdToDelete}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            deletePhotoModal.hide();
            btn.disabled = false;
            btn.innerHTML = originalText;

            if (data.success) {
                // Hide preview and show placeholder
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                const container = document.getElementById('deletePhotoContainer');
                const currentInfo = document.querySelector('.text-muted .text-success');

                if (preview) {
                    preview.src = '';
                    preview.style.display = 'none';
                }
                
                if (placeholder) {
                    placeholder.style.display = 'flex';
                    placeholder.style.alignItems = 'center';
                    placeholder.style.justifyContent = 'center';
                }

                if (container) {
                    container.remove();
                }

                if (currentInfo) {
                    currentInfo.closest('.mb-2').remove();
                }
                
                // Show success toast/alert if needed, currently just silent success based on user preference "no alert"
                // or we can use a toast notification here if available in the system
            } else {
                alert('Failed to delete photo: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            deletePhotoModal.hide();
            btn.disabled = false;
            btn.innerHTML = originalText;
            alert('An error occurred while deleting the photo');
        });
    }

    function previewAvatar(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        const deleteCheckbox = document.getElementById('delete_photo');

        if (file) {
            // Check file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('File size exceeds 2MB. Please choose a smaller file.');
                event.target.value = '';
                return;
            }

            // Check file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file (JPG, PNG, GIF).');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);

            // Uncheck delete photo if new file selected
            if (deleteCheckbox) {
                deleteCheckbox.checked = false;
            }
        } else {
            // If no new file and there's an existing photo
            <?php if (!empty($user->photo) && file_exists(FCPATH . $user->photo)): ?>
                preview.src = '<?= base_url($user->photo) ?>';
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            <?php else: ?>
                preview.style.display = 'none';
                placeholder.style.display = 'block';
            <?php endif; ?>
        }
    }
</script>

<?= $this->endSection() ?>