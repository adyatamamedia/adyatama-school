<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Gallery</h1>
    <a href="<?= base_url('dashboard/galleries') ?>" class="btn btn-secondary shadow-sm">Back</a>
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

                <form action="<?= base_url('dashboard/galleries/update/' . $gallery->id) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Gallery Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title', $gallery->title) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $gallery->description) ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="extracurricular_id" class="form-label">Extracurricular (Optional)</label>
                            <select class="form-select" id="extracurricular_id" name="extracurricular_id">
                                <option value="">-- None --</option>
                                <?php foreach($ekskuls as $eks): ?>
                                    <option value="<?= $eks->id ?>" <?= old('extracurricular_id', $gallery->extracurricular_id) == $eks->id ? 'selected' : '' ?>>
                                        <?= esc($eks->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft" <?= old('status', $gallery->status) == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= old('status', $gallery->status) == 'published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Gallery</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
