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

<form action="<?= base_url('dashboard/galleries/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Left Column (Main Content) -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Galeri <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Masukkan judul galeri" value="<?= old('title') ?>" required>
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="slug" name="slug" value="<?= old('slug') ?>" placeholder="auto-generated-from-title" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="generateSlug()">
                                <i class="fas fa-sync-alt"></i> Generate
                            </button>
                        </div>
                        <div class="form-text">URL-friendly version dari judul. Akan dicek untuk keunikan.</div>
                    </div>

                    <!-- Description (Summernote) -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control summernote" id="description" name="description"><?= old('description') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Sidebar) -->
        <div class="col-md-4">
            <!-- Author Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Author</strong>
                </div>
                <div class="card-body">
                    <?php if (current_user()->role == 'admin' || current_user()->role == 'operator'): ?>
                        <select class="form-select" id="author_id" name="author_id">
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user->id ?>" <?= (old('author_id') == $user->id || current_user()->id == $user->id) ? 'selected' : '' ?>>
                                    <?= esc($user->fullname) ?> (<?= esc($user->username) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="hidden" name="author_id" value="<?= current_user()->id ?>">
                        <p class="form-control-plaintext"><?= current_user()->fullname ?> (<?= current_user()->username ?>)</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Publish Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Publikasi</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= old('status') == 'published' ? 'selected' : 'selected' ?>>Published</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Extracurricular Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Ekstrakurikuler</strong>
                </div>
                <div class="card-body">
                    <select class="form-select" id="extracurricular_id" name="extracurricular_id">
                        <option value="">-- Tidak Ada --</option>
                        <?php foreach ($ekskuls as $eks): ?>
                            <option value="<?= $eks->id ?>" <?= old('extracurricular_id') == $eks->id ? 'selected' : '' ?>>
                                <?= esc($eks->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Opsional: Pilih ekstrakurikuler terkait</small>
                </div>
            </div>

            <!-- Featured Image (Cover) Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Featured Image</strong>
                </div>
                <div class="card-body">
                    <div class="input-group mb-2">
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#mediaModal">Select Image</button>
                        <input type="text" class="form-control" id="featured_image_display" readonly placeholder="No image selected">
                        <input type="hidden" id="featured_image" name="featured_image" value="<?= old('featured_image') ?>">
                    </div>
                    <!-- Image Preview -->
                    <div id="featured_image_preview" style="display: none;">
                        <img id="featured_image_preview_img" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="removeFeaturedImage()">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">Cover/thumbnail untuk galeri ini</small>
                </div>
            </div>

            <!-- Gallery Items Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Gallery Items</strong>
                    <span class="badge bg-primary ms-auto" id="selectedCount">0 selected</span>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#galleryItemsModal">
                        <i class="fas fa-images me-1"></i>Select Multiple Images
                    </button>

                    <!-- Hidden inputs for selected items -->
                    <div id="galleryItemsInputs"></div>

                    <!-- Preview Grid -->
                    <div id="galleryItemsPreview" class="row row-cols-3 g-2" style="display: none;">
                        <!-- Filled by JS -->
                    </div>
                    <small class="text-muted d-block mt-2">Klik & drag untuk select multiple. Bisa reorder nanti.</small>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Simpan Galeri
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>

<!-- Media Modal for Featured Image -->
<?= $this->include('admin/partials/media_modal_script') ?>

<!-- Gallery Items Modal -->
<?= $this->include('admin/partials/gallery_items_modal') ?>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Auto-generate slug when title changes
        document.getElementById('title').addEventListener('input', function() {
            if (document.getElementById('slug').value === '' || document.getElementById('slug').dataset.autoGenerated === 'true') {
                generateSlugFromTitle();
            }
        });
    });

    // Generate slug from title
    function generateSlugFromTitle() {
        const title = document.getElementById('title').value;
        const slug = createSlug(title);
        const slugInput = document.getElementById('slug');
        slugInput.value = slug;
        slugInput.dataset.autoGenerated = 'true';
    }

    // Manual generate slug button
    function generateSlug() {
        generateSlugFromTitle();
    }

    // Create URL-friendly slug
    function createSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with single dash
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing dashes
    }

    // Mark slug as manually edited when user types
    document.getElementById('slug').addEventListener('input', function() {
        if (this.value !== '') {
            this.dataset.autoGenerated = 'false';
        }
    });

    // Featured Image Functions (using same pattern as Posts/Pages)
    function removeFeaturedImage() {
        document.getElementById('featured_image').value = '';
        document.getElementById('featured_image_display').value = '';
        document.getElementById('featured_image_preview').style.display = 'none';
        document.getElementById('featured_image_preview_img').src = '';
    }

    function showFeaturedImagePreview(path) {
        const preview = document.getElementById('featured_image_preview');
        const img = document.getElementById('featured_image_preview_img');
        img.src = '<?= base_url() ?>' + path;
        preview.style.display = 'block';
    }
</script>

<?= $this->endSection() ?>