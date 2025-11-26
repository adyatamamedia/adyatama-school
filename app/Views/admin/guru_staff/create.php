<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('dashboard/guru-staff/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Left Column (Main Content) -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Row 1: Nama & NIP -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="<?= old('nama_lengkap') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai" value="<?= old('nip') ?>">
                        </div>
                    </div>

                    <!-- Row 2: Jabatan & Bidang -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Kepala Sekolah" value="<?= old('jabatan') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="bidang" class="form-label">Bidang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="bidang" name="bidang" placeholder="Contoh: Matematika" value="<?= old('bidang') ?>" required>
                        </div>
                    </div>

                    <!-- Row 3: Email & No HP -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" value="<?= old('email') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" value="<?= old('no_hp') ?>">
                        </div>
                    </div>

                    <!-- Bio (Summernote) -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio / Deskripsi</label>
                        <textarea class="form-control" id="bio" name="bio"><?= old('bio') ?></textarea>
                        <small class="text-muted">Opsional: Informasi singkat tentang guru/staff</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Sidebar) -->
        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card shadow mb-3">
                <div class="card-header bg-light">
                    <strong>Status</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="guru" <?= old('status') == 'guru' ? 'selected' : 'selected' ?>>Guru</option>
                            <option value="staff" <?= old('status') == 'staff' ? 'selected' : '' ?>>Staff</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', '1') == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-check-circle text-success me-1"></i>Aktif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Foto Card -->
            <div class="card shadow mb-3">
                <div class="card-header bg-light">
                    <strong>Foto Profil</strong>
                </div>
                <div class="card-body">
                    <div class="input-group mb-2">
                        <button class="btn btn-outline-secondary" type="button" id="selectFotoBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Select Image</button>
                        <input type="text" class="form-control" id="foto_display" readonly placeholder="No image selected">
                        <input type="hidden" id="foto" name="foto" value="<?= old('foto') ?>">
                    </div>
                    <!-- Image Preview -->
                    <div id="foto_preview" style="display: none;">
                        <img id="foto_preview_img" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="removeFoto()">
                            <i class="fas fa-times"></i> Remove Image
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100 shadow">
                <i class="fas fa-save me-1"></i>Simpan Data
            </button>
        </div>
    </div>
</form>

<!-- Media Modal -->
<?= $this->include('admin/partials/media_modal_script') ?>

<script>
    $(document).ready(function() {
        $('#bio').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Tulis bio singkat di sini...'
        });

        // Override selectMediaForInput AFTER modal script loaded
        setTimeout(function() {
            window.selectMediaForInput = function(path, id) {
                console.log('Guru Staff override - path:', path, 'id:', id);
                // Extract filename only (remove uploads/ prefix if exists)
                const filename = path.replace(/^\/?(uploads\/)?/, '');

                document.getElementById('foto').value = filename;
                document.getElementById('foto_display').value = filename;
                showFotoPreview(path);

                // Close modal
                const modalEl = document.getElementById('mediaModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            };
            console.log('Guru Staff override function set');
        }, 100);
    });

    // Foto Functions
    function removeFoto() {
        document.getElementById('foto').value = '';
        document.getElementById('foto_display').value = '';
        document.getElementById('foto_preview').style.display = 'none';
        document.getElementById('foto_preview_img').src = '';
    }

    function showFotoPreview(path) {
        const preview = document.getElementById('foto_preview');
        const img = document.getElementById('foto_preview_img');
        img.src = '<?= base_url() ?>' + path;
        preview.style.display = 'block';
    }
</script>

<?= $this->endSection() ?>