<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <!-- Removed redundant H1, relying on layout header -->
        <p class="mb-0 text-muted"><?= count($items) ?> items in this gallery</p>
    </div>
    <div>
        <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#mediaModal">
            <i class="fas fa-plus-circle"></i> Add Photos
        </button>
        <a href="<?= base_url('dashboard/galleries') ?>" class="btn btn-secondary shadow-sm">Back</a>
    </div>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<!-- Smaller Grid for Items -->
<div class="row row-cols-2 row-cols-md-6 g-3" id="galleryGrid">
    <?php if (!empty($items)) : ?>
        <?php foreach ($items as $item) : ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="ratio ratio-1x1">
                        <img src="<?= base_url($item->path) ?>" class="card-img-top object-fit-cover h-100" alt="Gallery Item">
                    </div>
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted" style="font-size: 0.7rem;">Order: <?= $item->order_num ?></small>
                        <a href="<?= base_url('dashboard/galleries/items/delete/' . $item->id) ?>" class="text-danger small" onclick="return confirm('Remove this photo from gallery?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-info text-center">No photos in this gallery yet. Click "Add Photos" to start.</div>
        </div>
    <?php endif; ?>
</div>

<!-- Media Selection Modal (Customized for Multiple Selection could be better, but single for now) -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Photos to Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mediaGridSelect" class="row row-cols-4 g-3">
                    <p class="text-center w-100">Loading media...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('mediaModal');
    const grid = document.getElementById('mediaGridSelect');
    
    modalEl.addEventListener('show.bs.modal', function () {
        fetch('<?= base_url('dashboard/api/media') ?>')
            .then(response => response.json())
            .then(data => {
                grid.innerHTML = '';
                if(data.length === 0) {
                    grid.innerHTML = '<p class="text-center w-100">No media found. Please upload in Media Library first.</p>';
                    return;
                }
                
                data.forEach(item => {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 cursor-pointer media-item" onclick="addMediaToGallery(${item.id})">
                            <img src="<?= base_url() ?>${item.path}" class="card-img-top" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-1 text-center">
                                <small class="text-truncate d-block">${item.caption}</small>
                            </div>
                        </div>
                    `;
                    grid.appendChild(col);
                });
            });
    });

    window.addMediaToGallery = function(mediaId) {
        // AJAX call to add item
        const formData = new FormData();
        formData.append('media_id', mediaId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // CSRF

        fetch('<?= base_url('dashboard/galleries/items/add/' . $gallery->id) ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Reload page to show new item
                location.reload(); 
            } else {
                alert('Failed to add item');
            }
        });
    };
});
</script>
<?= $this->endSection() ?>
