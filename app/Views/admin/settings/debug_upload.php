<!DOCTYPE html>
<html>
<head>
    <title>Debug Upload Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Debug File Upload Settings</h2>

        <form action="<?= base_url('dashboard/settings/update') ?>" method="post" enctype="multipart/form-data" class="border p-4 mb-4">
            <?= csrf_field() ?>
            <h4>Test All Image Uploads</h4>

            <div class="mb-3">
                <label for="site_logo" class="form-label">Site Logo</label>
                <input type="file" class="form-control" name="site_logo" id="site_logo" accept="image/*">
                <small class="form-text">Test site logo upload</small>
            </div>

            <div class="mb-3">
                <label for="seo_image" class="form-label">SEO Image</label>
                <input type="file" class="form-control" name="seo_image" id="seo_image" accept="image/*">
                <small class="form-text">Test SEO image upload</small>
            </div>

            <div class="mb-3">
                <label for="hero_bg_image" class="form-label">Hero Background Image</label>
                <input type="file" class="form-control" name="hero_bg_image" id="hero_bg_image" accept="image/*">
                <small class="form-text">Test hero background image upload</small>
            </div>

            <button type="submit" class="btn btn-primary">Test Upload</button>
            <a href="<?= base_url('dashboard/settings') ?>" class="btn btn-secondary">Back to Settings</a>
        </form>

        <div class="row">
            <div class="col-md-6">
                <h5>Server Check</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        PHP Upload Max File Size
                        <span class="badge bg-primary rounded-pill"><?= ini_get('upload_max_filesize') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        PHP Post Max Size
                        <span class="badge bg-primary rounded-pill"><?= ini_get('post_max_size') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        File Uploads Enabled
                        <span class="badge bg-<?= ini_get('file_uploads') ? 'success' : 'danger' ?> rounded-pill"><?= ini_get('file_uploads') ? 'YES' : 'NO' ?></span>
                    </li>
                </ul>
            </div>

            <div class="col-md-6">
                <h5>Directory Check</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        uploads/settings exists
                        <span class="badge bg-<?= is_dir(FCPATH . 'uploads/settings') ? 'success' : 'danger' ?> rounded-pill"><?= is_dir(FCPATH . 'uploads/settings') ? 'YES' : 'NO' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        uploads/settings writable
                        <span class="badge bg-<?= is_writable(FCPATH . 'uploads/settings') ? 'success' : 'danger' ?> rounded-pill"><?= is_writable(FCPATH . 'uploads/settings') ? 'YES' : 'NO' ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>