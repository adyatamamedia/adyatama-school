<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Data Siswa -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Siswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="180" class="fw-bold">Nama Lengkap</td>
                        <td width="20">:</td>
                        <td><?= esc($application['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NISN</td>
                        <td>:</td>
                        <td><?= esc($application['nisn'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jenis Kelamin</td>
                        <td>:</td>
                        <td>
                            <?php if ($application['jenis_kelamin'] == 'L'): ?>
                                <span class="badge bg-info">Laki-laki</span>
                            <?php else: ?>
                                <span class="badge bg-pink">Perempuan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td><?= esc($application['tempat_lahir']) ?>, <?= date('d F Y', strtotime($application['tanggal_lahir'])) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat</td>
                        <td>:</td>
                        <td><?= nl2br(esc($application['alamat'])) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Asal Sekolah</td>
                        <td>:</td>
                        <td><?= esc($application['asal_sekolah'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Orang Tua</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="180" class="fw-bold">Nama Orang Tua</td>
                        <td width="20">:</td>
                        <td><?= esc($application['nama_ortu']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No HP</td>
                        <td>:</td>
                        <td><?= esc($application['no_hp']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td>:</td>
                        <td><?= esc($application['email'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-file-image me-2"></i>Dokumen Lampiran</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Dokumen KK -->
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Dokumen Kartu Keluarga</h6>
                        <?php if ($application['dokumen_kk']): ?>
                            <?php
                            // Path di DB: dash/public/uploads/... → ambil dari uploads/ saja
                            $relativePath = str_replace('dash/public/', '', $application['dokumen_kk']);
                            $kkPath = base_url($relativePath);
                            $ext = strtolower(pathinfo($application['dokumen_kk'], PATHINFO_EXTENSION));
                            ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <a href="<?= $kkPath ?>" class="glightbox dokumen-thumbnail" data-title="Dokumen Kartu Keluarga">
                                    <img src="<?= $kkPath ?>" class="img-fluid border rounded" style="max-height: 200px;" alt="Dokumen KK">
                                </a>
                            <?php else: ?>
                                <a href="<?= $kkPath ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Dokumen
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>

                    <!-- Dokumen Akte -->
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Dokumen Akte Kelahiran</h6>
                        <?php if ($application['dokumen_akte']): ?>
                            <?php
                            $relativePath = str_replace('dash/public/', '', $application['dokumen_akte']);
                            $aktePath = base_url($relativePath);
                            $ext = strtolower(pathinfo($application['dokumen_akte'], PATHINFO_EXTENSION));
                            ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <a href="<?= $aktePath ?>" class="glightbox dokumen-thumbnail" data-title="Dokumen Akte Kelahiran">
                                    <img src="<?= $aktePath ?>" class="img-fluid border rounded" style="max-height: 200px;" alt="Dokumen Akte">
                                </a>
                            <?php else: ?>
                                <a href="<?= $aktePath ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Dokumen
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>

                    <!-- Pas Foto -->
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Pas Foto</h6>
                        <?php if ($application['pas_foto']): ?>
                            <?php
                            $relativePath = str_replace('dash/public/', '', $application['pas_foto']);
                            $pasfotoPath = base_url($relativePath);
                            $ext = strtolower(pathinfo($application['pas_foto'], PATHINFO_EXTENSION));
                            ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <a href="<?= $pasfotoPath ?>" class="glightbox dokumen-thumbnail" data-title="Pas Foto">
                                    <img src="<?= $pasfotoPath ?>" class="img-fluid border rounded" style="max-height: 200px;" alt="Pas Foto">
                                </a>
                            <?php else: ?>
                                <a href="<?= $pasfotoPath ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Dokumen
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>

                    <!-- Foto Ijazah -->
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Foto Ijazah</h6>
                        <?php if ($application['foto_ijazah']): ?>
                            <?php
                            $relativePath = str_replace('dash/public/', '', $application['foto_ijazah']);
                            $ijazahPath = base_url($relativePath);
                            $ext = strtolower(pathinfo($application['foto_ijazah'], PATHINFO_EXTENSION));
                            ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <a href="<?= $ijazahPath ?>" class="glightbox dokumen-thumbnail" data-title="Foto Ijazah">
                                    <img src="<?= $ijazahPath ?>" class="img-fluid border rounded" style="max-height: 200px;" alt="Foto Ijazah">
                                </a>
                            <?php else: ?>
                                <a href="<?= $ijazahPath ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Dokumen
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Status -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Pendaftaran</h5>
            </div>
            <div class="card-body">
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
                <div class="text-center mb-3">
                    <h3>
                        <span class="badge bg-<?= $statusColors[$application['status']] ?>">
                            <?= $statusLabels[$application['status']] ?>
                        </span>
                    </h3>
                </div>

                <div class="d-grid gap-2">
                    <form method="post" action="<?= base_url('dashboard/pendaftaran/update-status/' . $application['id']) ?>">
                        <?php if ($application['status'] == 'pending'): ?>
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i> Terima Pendaftaran
                            </button>
                        <?php elseif ($application['status'] == 'accepted'): ?>
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-undo me-2"></i> Kembalikan ke Pending
                            </button>
                        <?php endif; ?>
                    </form>
                </div>

                <hr>

                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Tanggal Daftar</td>
                    </tr>
                    <tr>
                        <td><strong><?= date('d F Y, H:i', strtotime($application['created_at'])) ?> WIB</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('dashboard/pendaftaran/export-doc/' . $application['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-file-word me-2"></i> Export ke DOC
                    </a>
                    <button type="button"
                        class="btn btn-danger"
                        onclick="showDeleteModal('<?= base_url('dashboard/pendaftaran/delete/' . $application['id']) ?>', 'Hapus pendaftaran \'<?= esc($application['nama_lengkap']) ?>\'?')">
                        <i class="fas fa-trash me-2"></i> Hapus Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<?= $this->include('admin/partials/delete_modal') ?>

<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize GLightbox
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    });
</script>

<style>
    .bg-pink {
        background-color: #e83e8c !important;
        color: white;
    }

    .dokumen-thumbnail {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dokumen-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<?= $this->endSection() ?>