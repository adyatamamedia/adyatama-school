<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create New Post</h1>
    <a href="<?= base_url('dashboard/posts') ?>" class="btn btn-secondary shadow-sm">Back</a>
</div>

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
                                        <label for="excerpt" class="form-label">Excerpt (Short Description)</label>
                                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= old('excerpt') ?></textarea>
                                        <div class="form-text">Brief summary for list views and SEO.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="10"><?= old('content') ?></textarea>
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
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>" <?= old('category_id') == $cat->id ? 'selected' : '' ?>>
                                            <?= esc($cat->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <?php if(current_user()->role == 'admin'): ?>
                            <div class="mb-3">
                                <label for="author_id" class="form-label">Author</label>
                                <select class="form-select" id="author_id" name="author_id">
                                    <?php foreach($users as $user): ?>
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
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" id="selectImageBtn" data-bs-toggle="modal" data-bs-target="#mediaModal">Select Image</button>
                                    <input type="text" class="form-control" id="featured_image_display" readonly placeholder="No image selected">
                                    <input type="hidden" id="featured_media_id" name="featured_media_id" value="<?= old('featured_media_id') ?>">
                                </div>
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

<?= $this->include('admin/partials/media_modal_script') ?>

<?= $this->endSection() ?>
