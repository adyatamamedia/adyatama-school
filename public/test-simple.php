<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Summernote Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/summernote/css/summernote-bs5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Simple Summernote Test</h1>

        <?php
        // Test if we can access the files
        $summernote_css = 'assets/vendor/summernote/css/summernote-bs5.min.css';
        $summernote_js = 'assets/vendor/summernote/js/summernote-bs5.min.js';

        echo '<div class="alert alert-info">';
        echo '<h5>File Checks:</h5>';
        echo '<ul>';
        echo '<li>CSS File: ' . (file_exists($summernote_css) ? '✓ EXISTS' : '✗ MISSING') . '</li>';
        echo '<li>JS File: ' . (file_exists($summernote_js) ? '✓ EXISTS' : '✗ MISSING') . '</li>';
        echo '</ul>';
        echo '</div>';
        ?>

        <div class="form-group">
            <label for="summernote">Content:</label>
            <textarea id="summernote" class="form-control" name="content">Test content!</textarea>
        </div>

        <button class="btn btn-primary mt-2" onclick="testEditor()">Test Editor</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/summernote/js/summernote-bs5.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('Summernote loaded:', typeof $.fn.summernote !== 'undefined');

            if (typeof $.fn.summernote !== 'undefined') {
                $('#summernote').summernote({
                    height: 200,
                    placeholder: 'Write here...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen']]
                    ]
                });
                console.log('Summernote initialized');
            } else {
                console.error('Summernote not available');
                alert('Summernote failed to load! Check console for errors.');
            }
        });

        function testEditor() {
            if ($('#summernote').summernote) {
                const content = $('#summernote').summernote('code');
                alert('Editor is working! Content length: ' + content.length + ' characters');
            } else {
                alert('Editor not initialized');
            }
        }
    </script>
</body>
</html>