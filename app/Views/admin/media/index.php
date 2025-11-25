<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Removed Redundant Title, Button Only -->
    <div></div> 
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fas fa-upload text-white-50"></i> Upload New
    </button>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Smaller Grid: row-cols-2 (mobile) row-cols-md-6 (desktop) -->
<div class="row row-cols-2 row-cols-md-6 g-3">
    <?php if (!empty($media)) : ?>
        <?php foreach ($media as $item) : ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="ratio ratio-1x1 bg-light position-relative group-action">
                        <?php if($item->type == 'image'): ?>
                            <img src="<?= base_url($item->path) ?>" class="card-img-top object-fit-cover h-100" alt="<?= esc($item->caption) ?>">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="fas fa-file fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay Edit Button -->
                        <div class="position-absolute top-0 end-0 p-1">
                            <button class="btn btn-sm btn-light text-primary shadow-sm opacity-75 hover-100" data-bs-toggle="modal" data-bs-target="#editModal<?= $item->id ?>">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <p class="card-text small text-truncate mb-1" title="<?= esc($item->caption) ?>">
                            <?= esc($item->caption) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted" style="font-size: 0.7rem;"><?= round($item->filesize / 1024) ?> KB</small>
                            <a href="<?= base_url('dashboard/media/delete/' . $item->id) ?>" class="text-danger small" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Caption Modal -->
            <div class="modal fade" id="editModal<?= $item->id ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form action="<?= base_url('dashboard/media/update/' . $item->id) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="modal-header">
                                <h6 class="modal-title">Edit Caption</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label class="form-label small">Caption</label>
                                    <input type="text" name="caption" class="form-control form-control-sm" value="<?= esc($item->caption) ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-light text-center">No media files found.</div>
        </div>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('dashboard/media/upload') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title">Upload Media</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label for="file" class="form-label">Select Image</label>
                <input class="form-control" type="file" id="file" name="file" accept="image/*" required>
                <div class="form-text">Max 2MB.</div>
            </div>
            <div class="mb-3">
                <label for="caption" class="form-label">Caption (Optional)</label>
                <input type="text" class="form-control" id="caption" name="caption" placeholder="Description">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
