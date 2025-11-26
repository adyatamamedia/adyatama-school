<!DOCTYPE html>
<html>
<head>
    <title>Settings Upload - SIMPLE VERSION</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 300px; padding: 5px; }
        .btn { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        .current-image { max-width: 200px; max-height: 150px; border: 1px solid #ddd; margin: 10px 0; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .tab-section { border: 1px solid #ddd; padding: 20px; margin: 10px 0; }
    </style>
<script>
function validateFileSize(input, maxSizeMB = 5) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // Convert to MB
        if (fileSize > maxSizeMB) {
            alert(`File "${input.name}" is too large (${fileSize.toFixed(2)}MB). Maximum size is ${maxSizeMB}MB.`);
            input.value = ''; // Clear the input
            return false;
        }
    }
    return true;
}

function validateFileType(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Check MIME type
        if (!allowedTypes.includes(file.type)) {
            alert(`File "${input.name}" has invalid type (${file.type}). Allowed types: JPG, PNG, GIF, WebP`);
            input.value = '';
            return false;
        }

        // Check file extension
        const extension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(extension)) {
            alert(`File "${input.name}" has invalid extension (.${extension}). Allowed: .jpg, .jpeg, .png, .gif, .webp`);
            input.value = '';
            return false;
        }
    }
    return true;
}

// Add event listeners to all file inputs
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            if (!validateFileSize(this) || !validateFileType(this)) {
                this.style.borderColor = 'red';
                this.style.backgroundColor = '#ffeeee';
            } else {
                this.style.borderColor = 'green';
                this.style.backgroundColor = '#eeffee';
            }
        });
    });

    // Form submission validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            fileInputs.forEach(function(input) {
                if (input.files && input.files[0]) {
                    if (!validateFileSize(input) || !validateFileType(input)) {
                        isValid = false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fix the file validation errors before submitting.');
            }
        });
    }
});
</script>
</head>
<body>
    <h1>SETTINGS UPLOAD - SIMPLE WORKING VERSION</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <strong>✅ Success:</strong> <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            <strong>❌ Error:</strong> <?= session()->getFlashdata('error') ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <hr style="margin: 10px 0; border-color: #f5c6cb;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        <div class="alert" style="background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">
            <strong>⚠️ Warning:</strong> <?= session()->getFlashdata('warning') ?>
            <?php if (session()->getFlashdata('successes')): ?>
                <hr style="margin: 10px 0; border-color: #ffeaa7;">
                <strong>✅ Successfully uploaded:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('successes') as $success): ?>
                        <li><?= $success ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (session()->getFlashdata('warnings')): ?>
                <strong>⚠️ Warnings:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('warnings') as $warning): ?>
                        <li><?= $warning ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <strong>❌ Errors:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('dashboard/settings-upload/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?php foreach ($groupedSettings as $group => $items): ?>
            <div class="tab-section">
                <h2><?= ucfirst($group) ?> Settings</h2>

                <?php foreach ($items as $setting): ?>
                    <div class="form-group">
                        <label for="<?= $setting->key_name ?>">
                            <?= $setting->description ?>
                            <small style="color: #666;">(<?= $setting->key_name ?>)</small>
                        </label>

                        <?php if ($setting->type == 'image'): ?>
                            <?php if (!empty($setting->value)): ?>
                                <div>
                                    <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" class="current-image">
                                    <br>
                                    <a href="<?= base_url('dashboard/settings-upload/delete-image/' . $setting->key_name) ?>"
                                       class="btn btn-danger"
                                       onclick="return confirm('Delete this image?')">
                                        Delete Current Image
                                    </a>
                                </div>
                            <?php endif; ?>

                            <input type="file" name="<?= $setting->key_name ?>" accept="image/*">
                            <div style="margin-top: 5px; font-size: 12px; color: #666;">
                                <strong>Requirements:</strong><br>
                                • Format: JPG, PNG, GIF, WebP<br>
                                • Size: Max 5MB<br>
                                • Dimensions: Min 50x50, Max 4000x4000px<br>
                                • Leave empty to keep current image
                            </div>

                        <?php elseif ($setting->type == 'textarea'): ?>
                            <textarea name="<?= $setting->key_name ?>" rows="3"><?= esc($setting->value) ?></textarea>

                        <?php elseif ($setting->type == 'boolean'): ?>
                            <select name="<?= $setting->key_name ?>">
                                <option value="1" <?= $setting->value == '1' ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= $setting->value == '0' ? 'selected' : '' ?>>No</option>
                            </select>

                        <?php else: ?>
                            <input type="text" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn">Save All Settings</button>
    </form>

    <hr>

    <h3>🔍 Debug Info:</h3>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <?php foreach ($groupedSettings as $group => $items): ?>
            <h4><?= ucfirst($group) ?> Group:</h4>
            <?php foreach ($items as $setting): ?>
                <?php if ($setting->type == 'image'): ?>
                    <div style="margin: 5px 0; padding: 5px; background: white; border-left: 4px solid <?= !empty($setting->value) ? '#28a745' : '#dc3545' ?>;">
                        <strong><?= $setting->key_name ?>:</strong>
                        <?php if (!empty($setting->value)): ?>
                            <span style="color: green;">✅ Has Image</span><br>
                            <small>Path: <?= $setting->value ?></small>
                            <?php if (file_exists(FCPATH . $setting->value)): ?>
                                <br><small style="color: green;">📁 File exists on server</small>
                                <?php
                                $fileSize = filesize(FCPATH . $setting->value);
                                echo '<br><small>📏 Size: ' . round($fileSize / 1024, 2) . ' KB</small>';
                                ?>
                            <?php else: ?>
                                <br><small style="color: orange;">⚠️ File missing on server</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: red;">❌ No Image</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <h3>📁 Upload Directory Status:</h3>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <?php
        $uploadDir = FCPATH . 'uploads/settings';
        $dirExists = is_dir($uploadDir);
        $dirWritable = $dirExists && is_writable($uploadDir);

        echo "<strong>Path:</strong> " . htmlspecialchars($uploadDir) . "<br>";
        echo "<strong>Exists:</strong> " . ($dirExists ? "✅ YES" : "❌ NO") . "<br>";
        echo "<strong>Writable:</strong> " . ($dirWritable ? "✅ YES" : "❌ NO") . "<br>";

        if ($dirExists) {
            $files = glob($uploadDir . '/*');
            echo "<strong>Files:</strong> " . count($files) . " files<br>";
            foreach ($files as $file) {
                $fileName = basename($file);
                $fileSize = filesize($file);
                echo "<small>• {$fileName} (" . round($fileSize / 1024, 1) . " KB)</small><br>";
            }
        }
        ?>
    </div>

    <h3>🛠️ PHP Upload Configuration:</h3>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <strong>File Uploads:</strong> <?= ini_get('file_uploads') ? '✅ Enabled' : '❌ Disabled' ?><br>
        <strong>Max File Size:</strong> <?= ini_get('upload_max_filesize') ?><br>
        <strong>Max POST Size:</strong> <?= ini_get('post_max_size') ?><br>
        <strong>Memory Limit:</strong> <?= ini_get('memory_limit') ?><br>
        <strong>Max Execution Time:</strong> <?= ini_get('max_execution_time') ?>s<br>
        <strong>Max Input Time:</strong> <?= ini_get('max_input_time') ?>s
    </div>
</body>
</html>