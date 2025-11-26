<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= base_url('dashboard/posts/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="postTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="true">Post Content</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO Settings</button>
                        </li>
                    </ul>

                    <div class="card shadow mb-4 border-top-0 rounded-top-0">
                        <div class="card-body">
                            <div class="tab-content" id="postTabsContent">
                                <!-- Content Tab -->
                                <div class="tab-pane fade show active" id="content-pane" role="tabpanel" aria-labelledby="content-tab">
                                    <?php if (session()->getFlashdata('errors')) : ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                                    <li><?= esc($error) ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Post Title</label>
                                        <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title') ?>" placeholder="Enter title here" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug (URL)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="slug" name="slug" value="<?= old('slug') ?>" placeholder="auto-generated-from-title" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="generateSlug()">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                        <div class="form-text">URL-friendly version of title. Will be checked for uniqueness.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="excerpt" class="form-label">Excerpt (Short Description)</label>
                                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= old('excerpt') ?></textarea>
                                        <div class="form-text">Brief summary for list views and SEO.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea class="form-control summernote" id="content" name="content"><?= old('content') ?></textarea>
                                    </div>
                                </div>

                                <!-- SEO Tab -->
                                <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
                                    <?= $this->include('admin/partials/seo_form') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Publish Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="post_type" class="form-label">Post Type</label>
                                <select class="form-select" id="post_type" name="post_type" onchange="toggleVideoUrl()">
                                    <option value="article" <?= old('post_type', 'article') == 'article' ? 'selected' : '' ?>>Artikel</option>
                                    <option value="announcement" <?= old('post_type') == 'announcement' ? 'selected' : '' ?>>Pengumuman</option>
                                    <option value="video" <?= old('post_type') == 'video' ? 'selected' : '' ?>>Video</option>
                                </select>
                            </div>

                            <div class="mb-3" id="video_url_wrapper" style="display: none;">
                                <label for="video_url" class="form-label">Video URL</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" value="<?= old('video_url') ?>" placeholder="https://youtube.com/watch?v=...">
                                <div class="form-text">YouTube, Vimeo, or direct video URL</div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" onchange="togglePublishedDate()">
                                    <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                                </select>
                            </div>

                            <div class="mb-3" id="published_at_wrapper" style="display: none;">
                                <label for="published_at" class="form-label">Publish Date & Time</label>
                                <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="<?= old('published_at', date('Y-m-d\TH:i')) ?>">
                                <div class="form-text">Leave empty to use current time</div>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>" <?= old('category_id') == $cat->id ? 'selected' : '' ?>>
                                            <?= esc($cat->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php if (current_user()->role == 'admin'): ?>
                                <div class="mb-3">
                                    <label for="author_id" class="form-label">Author</label>
                                    <select class="form-select" id="author_id" name="author_id">
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user->id ?>" <?= (old('author_id') == $user->id || current_user()->id == $user->id) ? 'selected' : '' ?>>
                                                <?= esc($user->fullname) ?> (@<?= esc($user->username) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <!-- Featured Image Selector -->
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-2">
                                    <button class="btn btn-outline-secondary" type="button" id="selectImageBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Select Image</button>
                                    <input type="text" class="form-control" id="featured_image_display" readonly placeholder="No image selected">
                                    <input type="hidden" id="featured_media_id" name="featured_media_id" value="<?= old('featured_media_id') ?>">
                                </div>
                                <!-- Image Preview -->
                                <div id="featured_image_preview" style="display: none;">
                                    <img id="featured_image_preview_img" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="removeFeaturedImage()">
                                        <i class="fas fa-times"></i> Remove Image
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" value="<?= old('tags') ?>" placeholder="Ketik untuk suggestions...">
                                <div class="form-text">Ketik beberapa huruf untuk melihat rekomendasi tag yang sudah ada</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="comments_enabled" name="comments_enabled" value="1" checked>
                                    <label class="form-check-label" for="comments_enabled">Allow Comments</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="react_enabled" name="react_enabled" value="1" checked>
                                    <label class="form-check-label" for="react_enabled">Allow Reactions</label>
                                </div>
                            </div>

                            <hr>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Save Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Media Selection Modal -->
<!-- Using Partial -->

<script>
    function toggleVideoUrl() {
        const postType = document.getElementById('post_type').value;
        const videoWrapper = document.getElementById('video_url_wrapper');
        videoWrapper.style.display = postType === 'video' ? 'block' : 'none';
    }

    function togglePublishedDate() {
        const status = document.getElementById('status').value;
        const publishedWrapper = document.getElementById('published_at_wrapper');
        publishedWrapper.style.display = status === 'published' ? 'block' : 'none';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleVideoUrl();
        togglePublishedDate();

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

    // Featured Image Preview Functions
    function removeFeaturedImage() {
        document.getElementById('featured_media_id').value = '';
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

    // Initialize Tagify for tags input
    (function() {
        // Existing tags from database
        var existingTags = <?= json_encode($existingTags ?? []) ?>;

        var input = document.getElementById('tags');

        // Initialize Tagify
        var tagify = new Tagify(input, {
            whitelist: existingTags,
            maxTags: 20,
            dropdown: {
                maxItems: 10,
                classname: "tags-dropdown",
                enabled: 0, // show suggestions on focus
                closeOnSelect: false
            },
            placeholder: "Ketik untuk suggestions...",
            originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(', ')
        });

        // Show dropdown on focus
        input.addEventListener('focus', function() {
            tagify.dropdown.show();
        });
    })();
</script>

<!-- Tagify CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<?= $this->include('admin/partials/media_modal_script') ?>
<?= $this->include('admin/partials/summernote_init') ?>
<?= $this->endSection() ?>