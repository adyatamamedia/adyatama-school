<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<!-- Cropper.js CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<style>
    .btn-group .btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    /* ... existing styles ... */
    .editor-container {
        max-height: 600px;
        background-color: #f8f9fa;
        text-align: center;
        overflow: hidden;
    }

    .editor-container img {
        max-width: 100%;
        max-height: 500px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Lightbox Trigger */
    .lightbox-trigger {
        cursor: pointer !important;
        transition: transform 0.2s ease;
    }

    .lightbox-trigger:hover {
        transform: scale(1.05);
    }

    /* Video Trigger */
    .video-trigger {
        cursor: pointer !important;
        transition: all 0.3s ease;
    }

    .video-trigger:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .video-trigger:hover i {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    /* Video in Lightbox */
    #lightbox-video {
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
    }

    #lightbox-video::-webkit-media-controls {
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Lightbox Styles */
    .lightbox-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 100000;
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lightbox-overlay.active {
        opacity: 1;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90vh;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 35px;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        padding: 5px 12px;
        z-index: 10000;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .lightbox-close:hover {
        background: rgba(255, 77, 77, 0.8);
        transform: rotate(90deg);
    }

    .lightbox-caption {
        color: #fff;
        margin-top: 10px;
        font-size: 16px;
        text-align: center;
        background: rgba(0, 0, 0, 0.6);
        padding: 8px 16px;
        border-radius: 20px;
    }
</style>

<div class="row mb-3">
    <div class="col-md-3">
        <!-- Search Box -->
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="searchMedia" placeholder="Cari media...">
            <button class="btn btn-outline-secondary" type="button" onclick="searchMedia()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-9 text-end">
        <!-- Sort By -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="updateFilters()" id="sortBy">
            <option value="newest" <?= $sortBy == 'newest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $sortBy == 'oldest' ? 'selected' : '' ?>>Terlama</option>
            <option value="name_asc" <?= $sortBy == 'name_asc' ? 'selected' : '' ?>>Nama A-Z</option>
            <option value="name_desc" <?= $sortBy == 'name_desc' ? 'selected' : '' ?>>Nama Z-A</option>
            <option value="size_asc" <?= $sortBy == 'size_asc' ? 'selected' : '' ?>>Ukuran Terkecil</option>
            <option value="size_desc" <?= $sortBy == 'size_desc' ? 'selected' : '' ?>>Ukuran Terbesar</option>
        </select>

        <!-- Items Per Page -->
        <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" onchange="updateFilters()" id="perPage">
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
        </select>

        <!-- View Toggle -->
        <div class="btn-group btn-group-sm me-2" role="group" aria-label="View toggle">
            <button type="button" class="btn btn-outline-secondary" id="gridViewBtn" onclick="setView('grid')" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary" id="listViewBtn" onclick="setView('list')" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <?php if (current_user()->role !== 'guru'): ?>
            <button type="button" class="btn btn-danger btn-sm me-2" id="bulkDeleteBtn" style="display:none;" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Hapus (<span id="selectedCount">0</span>)
            </button>
        <?php endif; ?>
        <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Grid View -->
<div class="row row-cols-2 row-cols-md-6 g-3" id="mediaGridView">
    <?php if (!empty($media)) : ?>
        <?php foreach ($media as $item) : ?>
            <div class="col media-item" data-caption="<?= strtolower(esc($item->caption)) ?>">
                <div class="card h-100 shadow-sm">
                    <div class="ratio ratio-1x1 bg-light position-relative group-action">
                        <?php if ($item->type == 'image'): ?>
                            <img src="<?= base_url($item->path) ?>"
                                class="card-img-top object-fit-cover h-100 lightbox-trigger"
                                alt="<?= esc($item->caption) ?>"
                                data-lightbox-src="<?= base_url($item->path) ?>"
                                data-lightbox-caption="<?= esc($item->caption) ?>">
                        <?php elseif ($item->type == 'video'): ?>
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-gradient text-white video-trigger"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                data-video-src="<?= base_url($item->path) ?>"
                                data-video-caption="<?= esc($item->caption) ?>">
                                <i class="fas fa-play-circle fa-4x mb-2 opacity-75"></i>
                                <span class="badge bg-dark bg-opacity-75 mt-2 px-3 py-2" style="font-size: 0.7rem;">
                                    <i class="fas fa-video me-1"></i> CLICK TO PLAY
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="fas fa-file fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Checkbox for Selection (Top Left with bigger hitbox) - Hide for Guru -->
                        <?php if (current_user()->role !== 'guru'): ?>
                            <div class="position-absolute top-0 start-0 p-2" style="z-index: 10; pointer-events: none;">
                                <input type="checkbox" class="form-check-input media-checkbox" value="<?= $item->id ?>" onchange="updateBulkDeleteButton()" style="width: 20px; height: 20px; cursor: pointer; pointer-events: auto;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2">
                        <p class="card-text small text-truncate mb-1" title="<?= esc($item->caption) ?>">
                            <?= esc($item->caption) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted" style="font-size: 0.7rem;"><?= round($item->filesize / 1024) ?> KB</small>
                            <div>
                                <button class="btn btn-sm btn-link text-primary p-0 me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $item->id ?>" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <?php if (current_user()->role !== 'guru'): ?>
                                    <a href="<?= base_url('dashboard/media/delete/' . $item->id) ?>" class="text-danger small" onclick="return confirm('Delete?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
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

<!-- List View -->
<div class="table-responsive d-none" id="mediaListView">
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if (current_user()->role !== 'guru'): ?>
                    <th width="40">
                        <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                <?php endif; ?>
                <th width="80">Preview</th>
                <th>Caption</th>
                <th width="100">Size</th>
                <th width="150">Date</th>
                <th width="100" class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($media)) : ?>
                <?php foreach ($media as $item) : ?>
                    <tr class="media-item-list" data-caption="<?= strtolower(esc($item->caption)) ?>">
                        <?php if (current_user()->role !== 'guru'): ?>
                            <td>
                                <input type="checkbox" class="form-check-input media-checkbox" value="<?= $item->id ?>" onchange="updateBulkDeleteButton()">
                            </td>
                        <?php endif; ?>
                        <td>
                            <?php if ($item->type == 'image'): ?>
                                <img src="<?= base_url($item->path) ?>" alt="<?= esc($item->caption) ?>" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                            <?php elseif ($item->type == 'video'): ?>
                                <div class="d-flex flex-column align-items-center justify-content-center rounded text-white video-trigger"
                                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                    data-video-src="<?= base_url($item->path) ?>"
                                    data-video-caption="<?= esc($item->caption) ?>">
                                    <i class="fas fa-play-circle fa-lg"></i>
                                    <small style="font-size: 0.5rem; margin-top: 2px;">PLAY</small>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-file fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= esc($item->caption) ?></strong>
                            <br>
                            <small class="text-muted"><?= esc($item->path) ?></small>
                        </td>
                        <td><?= round($item->filesize / 1024) ?> KB</td>
                        <td>
                            <small class="text-muted"><?= date('d M Y', strtotime($item->created_at)) ?></small>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $item->id ?>" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <?php if (current_user()->role !== 'guru'): ?>
                                <a href="<?= base_url('dashboard/media/delete/' . $item->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">No media files found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($pager->getPageCount() > 1): ?>
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <?= $pager->links('default', 'bootstrap_pagination', ['sort' => $sortBy, 'per_page' => $perPage]) ?>
            </nav>
            <div class="text-center text-muted small">
                Menampilkan <?= count($media) ?> dari <?= $pager->getTotal() ?> media
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Edit Media Modals -->
<?php if (!empty($media)) : ?>
    <?php foreach ($media as $item) : ?>
        <div class="modal fade" id="editModal<?= $item->id ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="mediaTab<?= $item->id ?>" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="details-tab-<?= $item->id ?>" data-bs-toggle="tab" data-bs-target="#details-<?= $item->id ?>" type="button" role="tab">Details</button>
                            </li>
                            <?php if ($item->type == 'image'): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="editor-tab-<?= $item->id ?>" data-bs-toggle="tab" data-bs-target="#editor-<?= $item->id ?>" type="button" role="tab" onclick="initEditor('<?= $item->id ?>', '<?= base_url($item->path) ?>')">Image Editor</button>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="tab-content" id="mediaTabContent<?= $item->id ?>">
                            <!-- Details Tab -->
                            <div class="tab-pane fade show active" id="details-<?= $item->id ?>" role="tabpanel">
                                <form action="<?= base_url('dashboard/media/update/' . $item->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            <?php if ($item->type == 'image'): ?>
                                                <img src="<?= base_url($item->path) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 300px;" id="preview-img-<?= $item->id ?>">
                                            <?php else: ?>
                                                <div class="p-5 bg-light rounded border">
                                                    <i class="fas fa-file fa-4x text-muted"></i>
                                                    <p class="mt-2"><?= esc($item->type) ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Caption</label>
                                                <input type="text" name="caption" class="form-control" value="<?= esc($item->caption) ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">File Info</label>
                                                <ul class="list-group list-group-flush small">
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Size:</span>
                                                        <strong><?= round($item->filesize / 1024) ?> KB</strong>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Uploaded:</span>
                                                        <strong><?= date('d M Y H:i', strtotime($item->created_at)) ?></strong>
                                                    </li>
                                                    <li class="list-group-item text-break">
                                                        <span>Path:</span><br>
                                                        <span class="text-muted"><?= $item->path ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Save Details</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Editor Tab -->
                            <?php if ($item->type == 'image'): ?>
                                <div class="tab-pane fade" id="editor-<?= $item->id ?>" role="tabpanel">
                                    <div class="editor-toolbar mb-2 d-flex gap-2 justify-content-center flex-wrap">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="rotateImage('<?= $item->id ?>', 'left')">
                                            <i class="fas fa-undo"></i> Rotate Left
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="rotateImage('<?= $item->id ?>', 'right')">
                                            <i class="fas fa-redo"></i> Rotate Right
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-crop-<?= $item->id ?>" onclick="toggleCrop('<?= $item->id ?>')">
                                            <i class="fas fa-crop"></i> Enable Crop
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetEditor('<?= $item->id ?>')">
                                            <i class="fas fa-sync"></i> Reset
                                        </button>
                                    </div>

                                    <div class="editor-container border rounded" id="editor-container-<?= $item->id ?>">
                                        <img id="editor-img-<?= $item->id ?>" src="" alt="Editor">
                                    </div>

                                    <div class="d-grid mt-3">
                                        <button type="button" class="btn btn-success" onclick="saveImageChanges('<?= $item->id ?>')">
                                            <i class="fas fa-save"></i> Save Image Changes
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('dashboard/media/upload-multiple') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Upload Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="files" class="form-label">Select Images or Videos (Multiple)</label>
                        <input class="form-control" type="file" id="files" name="files[]" accept="image/*,video/*" multiple required>
                        <div class="form-text">
                            <strong>Images:</strong> JPG, PNG, GIF, WEBP (Max 10MB per file)<br>
                            <strong>Videos:</strong> MP4, WEBM, MOV, AVI, MKV, FLV, WMV, MPEG (Max 100MB per file)
                        </div>
                    </div>
                    <div id="filePreview" class="row row-cols-3 g-2 mb-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox Overlay -->
<div id="lightbox" class="lightbox-overlay">
    <div class="lightbox-content">
        <button type="button" class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightbox-img" src="" alt="" class="lightbox-image" style="display:none;">
        <video id="lightbox-video" class="lightbox-image" controls style="display:none;">
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="lightbox-caption" class="lightbox-caption"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    // Image Editor Logic
    let cropperInstances = {};

    function initEditor(id, src) {
        const img = document.getElementById('editor-img-' + id);
        // Only load if empty to avoid reload flicker
        if (!img.getAttribute('src')) {
            img.src = src;
        }
    }

    function toggleCrop(id) {
        const img = document.getElementById('editor-img-' + id);
        const btn = document.getElementById('btn-crop-' + id);

        if (cropperInstances[id]) {
            // Destroy cropper
            cropperInstances[id].destroy();
            delete cropperInstances[id];
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-primary');
            btn.innerHTML = '<i class="fas fa-crop"></i> Enable Crop';
        } else {
            // Init cropper
            cropperInstances[id] = new Cropper(img, {
                viewMode: 1,
                responsive: true,
                background: false
            });
            btn.classList.add('active', 'btn-primary');
            btn.classList.remove('btn-outline-primary');
            btn.innerHTML = '<i class="fas fa-times"></i> Disable Crop';
        }
    }

    function rotateImage(id, direction) {
        // No confirmation for rotation (instant action)
        const action = direction === 'left' ? 'rotate_left' : 'rotate_right';

        // Add visual feedback (e.g., disable buttons temporarily or show spinner)
        const editorContainer = document.getElementById('editor-container-' + id);
        editorContainer.style.opacity = '0.5';

        sendEditorCommand(id, {
            action: action
        });
    }

    function resetEditor(id) {
        // Reload original image (cache bust)
        const img = document.getElementById('editor-img-' + id);
        const currentSrc = img.src.split('?')[0];
        img.src = currentSrc + '?t=' + new Date().getTime();

        if (cropperInstances[id]) {
            cropperInstances[id].destroy();
            delete cropperInstances[id];
            const btn = document.getElementById('btn-crop-' + id);
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-primary');
            btn.innerHTML = '<i class="fas fa-crop"></i> Enable Crop';
        }
    }

    function saveImageChanges(id) {
        console.log('saveImageChanges called for id:', id);
        console.log('Cropper instance exists?', !!cropperInstances[id]);

        // Check if cropping is active
        if (cropperInstances[id]) {
            // Send crop data to server
            const cropData = cropperInstances[id].getData();
            const data = {
                action: 'crop',
                x: cropData.x,
                y: cropData.y,
                width: cropData.width,
                height: cropData.height
            };
            console.log('Sending crop data:', data);
            sendEditorCommand(id, data);
        } else {
            // No crop active, just close modal and redirect to media library
            // (rotate changes are already saved to server)
            console.log('No cropper active, redirecting to media library...');
            window.location.href = '<?= base_url('dashboard/media') ?>';
        }
    }

    function sendEditorCommand(id, data) {
        // Add CSRF
        data['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';

        // Show loading state
        const editorContainer = document.getElementById('editor-container-' + id);
        if (editorContainer) editorContainer.style.opacity = '0.5';

        $.ajax({
            url: '<?= base_url('dashboard/media/edit-image/') ?>' + id,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Response received:', response);
                console.log('Data action:', data.action);

                // Restore opacity
                if (editorContainer) editorContainer.style.opacity = '1';

                if (response && response.success) {
                    // Redirect ONLY if crop action
                    if (data.action === 'crop') {
                        console.log('Crop successful! Redirecting to media library...');
                        window.location.href = '<?= base_url('dashboard/media') ?>';
                        return;
                    }

                    // For rotate actions, update images on page (don't redirect)
                    console.log('Rotate successful! Updating images...');
                    const newSrc = response.new_url;

                    // Update Editor Image
                    const editorImg = document.getElementById('editor-img-' + id);
                    if (editorImg) editorImg.src = newSrc;

                    // Update Preview Image in Details tab
                    const previewImg = document.getElementById('preview-img-' + id);
                    if (previewImg) previewImg.src = newSrc;

                    // Reset Cropper if it was active
                    if (cropperInstances[id]) {
                        cropperInstances[id].replace(newSrc);
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Restore opacity
                if (editorContainer) editorContainer.style.opacity = '1';

                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                alert('AJAX Error: ' + error);
            }
        });
    }

    // Lightbox Functions
    function openLightbox(src, caption, type = 'image') {
        console.log('Opening lightbox:', src, 'type:', type);
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        const video = document.getElementById('lightbox-video');
        const captionEl = document.getElementById('lightbox-caption');

        if (!lightbox || !img || !video) {
            console.error('Lightbox elements not found!');
            return;
        }

        // Reset both elements
        img.style.display = 'none';
        video.style.display = 'none';
        img.src = '';
        video.pause();
        video.querySelector('source').src = '';

        // Show appropriate element
        if (type === 'video') {
            video.querySelector('source').src = src;
            video.load();
            video.style.display = 'block';
        } else {
            img.src = src;
            img.style.display = 'block';
        }

        captionEl.textContent = caption || '';

        lightbox.style.display = 'flex';
        setTimeout(() => {
            lightbox.classList.add('active');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        console.log('Closing lightbox');
        const lightbox = document.getElementById('lightbox');
        if (!lightbox) return;

        lightbox.classList.remove('active');

        // Stop video if playing
        const video = document.getElementById('lightbox-video');
        if (video) {
            video.pause();
            video.currentTime = 0;
        }

        setTimeout(() => {
            lightbox.style.display = 'none';
            const img = document.getElementById('lightbox-img');
            if (img) img.src = '';
            if (video) video.querySelector('source').src = '';
        }, 300);

        document.body.style.overflow = '';
    }

    // Initialize Lightbox
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing lightbox...');

        // Add click event to all lightbox triggers (images and videos)
        document.addEventListener('click', function(e) {
            // Check if clicked element or parent has lightbox-trigger class
            let target = e.target;

            // Traverse up to find lightbox-trigger
            while (target && !target.classList.contains('lightbox-trigger') && !target.classList.contains('video-trigger')) {
                target = target.parentElement;
                if (!target || target === document.body) break;
            }

            if (target && target.classList.contains('lightbox-trigger')) {
                e.preventDefault();
                const src = target.getAttribute('data-lightbox-src');
                const caption = target.getAttribute('data-lightbox-caption');
                console.log('Image clicked:', src);
                openLightbox(src, caption, 'image');
            } else if (target && target.classList.contains('video-trigger')) {
                e.preventDefault();
                const src = target.getAttribute('data-video-src');
                const caption = target.getAttribute('data-video-caption');
                console.log('Video clicked:', src);
                openLightbox(src, caption, 'video');
            }
        });

        // Close on overlay click
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLightbox();
                }
            });
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const lb = document.getElementById('lightbox');
                if (lb && lb.classList.contains('active')) {
                    closeLightbox();
                }
            }
        });

        console.log('Lightbox initialized successfully');
    });

    // Update filters (sort and per page)
    function updateFilters() {
        const sortBy = document.getElementById('sortBy').value;
        const perPage = document.getElementById('perPage').value;
        const currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('sort', sortBy);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.delete('page'); // Reset to page 1

        window.location.href = currentUrl.toString();
    }

    // View Toggle
    let currentView = localStorage.getItem('mediaView') || 'grid';

    document.addEventListener('DOMContentLoaded', function() {
        setView(currentView, false);
    });

    function setView(view, savePreference = true) {
        currentView = view;

        const gridView = document.getElementById('mediaGridView');
        const listView = document.getElementById('mediaListView');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'grid') {
            gridView.classList.remove('d-none');
            listView.classList.add('d-none');
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            gridView.classList.add('d-none');
            listView.classList.remove('d-none');
            gridBtn.classList.remove('active');
            listBtn.classList.add('active');
        }

        if (savePreference) {
            localStorage.setItem('mediaView', view);
        }
    }

    // Toggle select all (for list view)
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.media-checkbox');

        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });

        updateBulkDeleteButton();
    }

    // Preview selected files
    document.getElementById('files').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';

        const files = Array.from(e.target.files);
        files.forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col';

            // Check if file is image or video
            const isImage = file.type.startsWith('image/');
            const isVideo = file.type.startsWith('video/');

            if (isImage) {
                // Preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    col.innerHTML = `
                    <div class="card">
                        <img src="${event.target.result}" class="card-img-top" style="height: 80px; object-fit: cover;">
                        <div class="card-body p-1 text-center">
                            <small class="text-truncate d-block">${file.name}</small>
                        </div>
                    </div>
                `;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            } else if (isVideo) {
                // Show video icon
                col.innerHTML = `
                <div class="card">
                    <div class="d-flex flex-column align-items-center justify-content-center text-white" style="height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-video fa-2x mb-1"></i>
                        <span class="badge bg-dark bg-opacity-75" style="font-size: 0.6rem;">VIDEO</span>
                    </div>
                    <div class="card-body p-1 text-center">
                        <small class="text-truncate d-block">${file.name}</small>
                    </div>
                </div>
            `;
                preview.appendChild(col);
            } else {
                // Other file types
                col.innerHTML = `
                <div class="card">
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 80px;">
                        <i class="fas fa-file fa-2x text-muted"></i>
                    </div>
                    <div class="card-body p-1 text-center">
                        <small class="text-truncate d-block">${file.name}</small>
                    </div>
                </div>
            `;
                preview.appendChild(col);
            }
        });
    });

    // Search functionality (works for both views)
    function searchMedia() {
        const searchTerm = document.getElementById('searchMedia').value.toLowerCase();

        // Grid view items
        const gridItems = document.querySelectorAll('.media-item');
        gridItems.forEach(item => {
            const caption = item.getAttribute('data-caption');
            if (caption.includes(searchTerm)) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });

        // List view items
        const listItems = document.querySelectorAll('.media-item-list');
        listItems.forEach(item => {
            const caption = item.getAttribute('data-caption');
            if (caption.includes(searchTerm)) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
    }

    // Search on Enter key
    document.getElementById('searchMedia').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchMedia();
        }
    });

    // Update bulk delete button visibility
    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.media-checkbox:checked');
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        const count = document.getElementById('selectedCount');

        if (checkboxes.length > 0) {
            bulkBtn.style.display = 'inline-block';
            count.textContent = checkboxes.length;
        } else {
            bulkBtn.style.display = 'none';
        }
    }

    // Bulk delete function
    function bulkDelete() {
        const checkboxes = document.querySelectorAll('.media-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            alert('Pilih media yang ingin dihapus');
            return;
        }

        if (!confirm(`Hapus ${ids.length} media terpilih?`)) {
            return;
        }

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('dashboard/media/bulk-delete') ?>';

        // Add CSRF token
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '<?= csrf_token() ?>';
        csrf.value = '<?= csrf_hash() ?>';
        form.appendChild(csrf);

        // Add IDs
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>
<?= $this->endSection() ?>