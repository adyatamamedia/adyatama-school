<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>


<div class="row">
    <div class="col-lg-12">
        <form action="<?= base_url('dashboard/pages/update/' . $page->id) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="pageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="true">Konten Halaman</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO Settings</button>
                        </li>
                    </ul>

                    <div class="card shadow mb-4 border-top-0 rounded-top-0">
                        <div class="card-body">
                            <div class="tab-content" id="pageTabsContent">
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
                                        <label for="title" class="form-label">Judul Halaman <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title', $page->title) ?>" placeholder="Masukkan judul halaman" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Konten</label>
                                        <textarea class="form-control summernote" id="content" name="content"><?= old('content', $page->content) ?></textarea>
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
                            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Publikasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="draft" <?= old('status', $page->status) == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= old('status', $page->status) == 'published' ? 'selected' : '' ?>>Published</option>
                                </select>
                            </div>

                            <!-- Featured Image Selector -->
                            <div class="mb-3">
                                <label class="form-label">Gambar Unggulan</label>
                                <div class="input-group mb-2">
                                    <button class="btn btn-outline-secondary" type="button" id="selectImageBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Pilih Gambar</button>
                                    <input type="text" class="form-control" id="featured_image_display" readonly placeholder="Belum ada gambar" value="<?= old('featured_image', $page->featured_image) ?>">
                                    <input type="hidden" id="featured_image" name="featured_image" value="<?= old('featured_image', $page->featured_image) ?>">
                                </div>
                                <!-- Image Preview -->
                                <div id="featured_image_preview" style="display: <?= $page->featured_image ? 'block' : 'none' ?>;">
                                    <img id="featured_image_preview_img" src="<?= $page->featured_image ? base_url($page->featured_image) : '' ?>" alt="Preview" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="removeFeaturedImage()">
                                        <i class="fas fa-times"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <hr>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Halaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->include('admin/partials/media_modal_script') ?>
<?= $this->include('admin/partials/summernote_init') ?>

<script>
    // Featured Image Functions
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