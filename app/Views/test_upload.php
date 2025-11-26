<!DOCTYPE html>
<html>
<head>
    <title>Test Upload</title>
</head>
<body>
    <h2>Test File Upload</h2>
    <form action="<?= base_url('test-upload/process') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label>Hero BG Image:</label>
        <input type="file" name="hero_bg_image" accept="image/*">
        <br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
