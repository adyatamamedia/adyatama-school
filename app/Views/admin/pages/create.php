<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create New Page</h1>
    <a href="<?= base_url('dashboard/pages') ?>" class="btn btn-secondary shadow-sm">Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <form action="<?= base_url('dashboard/pages/create') ?>" method="post">
            <?= csrf_field() ?>
            
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="pageTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="true">Content</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO</button>
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
                                <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="10"><?= old('content') ?></textarea>
                            </div>

                             <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                     <label class="form-label">Featured Image</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="selectPhotoBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Select</button>
                                        <input type="text" class="form-control" id="foto" name="featured_image" value="<?= old('featured_image') ?>" readonly placeholder="No image selected">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Tab -->
                        <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
                            <?= $this->include('admin/partials/seo_form') ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">Save Page</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->include('admin/partials/media_modal_script') ?>

<?= $this->endSection() ?>
