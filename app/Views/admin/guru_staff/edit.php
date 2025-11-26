<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Guru/Staff</h1>
    <a href="<?= base_url('dashboard/guru-staff') ?>" class="btn btn-secondary shadow-sm">
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

<form action="<?= base_url('dashboard/guru-staff/update/' . $staff->id) ?>" method="post">
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
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="<?= old('nama_lengkap', $staff->nama_lengkap) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai" value="<?= old('nip', $staff->nip) ?>">
                        </div>
                    </div>

                    <!-- Row 2: Jabatan & Bidang -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Kepala Sekolah" value="<?= old('jabatan', $staff->jabatan) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="bidang" class="form-label">Bidang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="bidang" name="bidang" placeholder="Contoh: Matematika" value="<?= old('bidang', $staff->bidang) ?>" required>
                        </div>
                    </div>

                    <!-- Row 3: Email & No HP -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" value="<?= old('email', $staff->email) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" value="<?= old('no_hp', $staff->no_hp) ?>">
                        </div>
                    </div>

                    <!-- Bio (Summernote) -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio / Deskripsi</label>
                        <textarea class="form-control summernote" id="bio" name="bio"><?= old('bio', $staff->bio) ?></textarea>
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
                            <option value="guru" <?= old('status', $staff->status) == 'guru' ? 'selected' : '' ?>>Guru</option>
                            <option value="staff" <?= old('status', $staff->status) == 'staff' ? 'selected' : '' ?>>Staff</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $staff->is_active) ? 'checked' : '' ?>>
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
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#mediaModal">
                            <i class="fas fa-image"></i> Pilih Foto
                        </button>
                        <input type="text" class="form-control form-control-sm" id="foto_display" readonly placeholder="Belum ada foto" value="<?= $staff->foto ?? '' ?>">
                        <input type="hidden" id="foto" name="foto" value="<?= old('foto', $staff->foto) ?>">
                    </div>
                    <!-- Foto Preview -->
                    <div id="foto_preview" style="display: <?= $staff->foto ? 'block' : 'none' ?>;">
                        <img id="foto_preview_img" src="<?= $staff->foto ? base_url('uploads/' . $staff->foto) : '' ?>" alt="Foto" class="img-fluid rounded border" style="width: 100%; height: 200px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="removeFoto()">
                            <i class="fas fa-times"></i> Hapus Foto
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">Foto profil guru/staff</small>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card shadow mb-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Media Modal -->
<?= $this->include('admin/partials/media_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#bio').summernote({
        height: 200,
        placeholder: 'Tulis bio atau deskripsi singkat tentang guru/staff...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'help']]
        ]
    });
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
