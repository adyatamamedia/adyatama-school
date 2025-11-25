<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Guru/Staff</h1>
    <a href="<?= base_url('dashboard/guru-staff') ?>" class="btn btn-secondary shadow-sm">Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('dashboard/guru-staff/update/' . $staff->id) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap', $staff->nama_lengkap) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="<?= old('nip', $staff->nip) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan (Position)</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= old('jabatan', $staff->jabatan) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="bidang" class="form-label">Bidang (Field/Subject)</label>
                            <input type="text" class="form-control" id="bidang" name="bidang" value="<?= old('bidang', $staff->bidang) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $staff->email) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= old('no_hp', $staff->no_hp) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="guru" <?= old('status', $staff->status) == 'guru' ? 'selected' : '' ?>>Guru</option>
                                <option value="staff" <?= old('status', $staff->status) == 'staff' ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label">Status Active</label>
                             <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $staff->is_active) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="selectPhotoBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Select Photo</button>
                            <input type="text" class="form-control" id="foto" name="foto" value="<?= old('foto', $staff->foto) ?>" readonly placeholder="No photo selected">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/partials/media_modal_script') ?>

<?= $this->endSection() ?>
