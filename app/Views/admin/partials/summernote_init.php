<!-- Summernote Editor Initialization -->
<script>
/**
 * Initialize Summernote with proper configuration
 */
function initializeSummernote() {
    console.log('initializeSummernote called');

    // Check if dependencies are ready
    if (typeof $ === 'undefined') {
        console.error('jQuery not available');
        setTimeout(initializeSummernote, 200);
        return;
    }

    if (typeof $.fn.summernote === 'undefined') {
        console.error('Summernote not available');
        setTimeout(initializeSummernote, 200);
        return;
    }

    // Check if textarea exists
    if ($('.summernote').length === 0) {
        console.log('No .summernote elements found');
        return;
    }

    // Check if already initialized
    if ($('.summernote').next('.note-editor').length > 0) {
        console.log('Summernote already initialized');
        return;
    }

    try {
        console.log('Initializing Summernote...');

        // Initialize Summernote
        $('.summernote').summernote({
            height: 400,
            minHeight: 200,
            focus: false,
            placeholder: 'Write your content here...',
            tabsize: 2,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onInit: function() {
                    console.log('✅ Summernote initialized successfully');
                },
                onImageUpload: function(files) {
                    console.log('Image upload initiated');
                    // Handle image upload
                    uploadSummernoteImage(files[0], $(this));
                }
            }
        });

        console.log('Summernote initialization completed');

    } catch (error) {
        console.error('Failed to initialize Summernote:', error);
    }
}

/**
 * Upload image for Summernote
 */
function uploadSummernoteImage(file, editor) {
    const formData = new FormData();
    formData.append('file', file);

    // Show loading state
    const loadingImg = $('<img>').attr('src', 'https://via.placeholder.com/100x100?text=Uploading...');
    editor.summernote('insertNode', loadingImg[0]);

    $.ajax({
        url: '<?= base_url('dashboard/summernote/upload') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                // Remove loading image
                loadingImg.remove();
                // Insert uploaded image
                const uploadedImg = $('<img>').attr('src', response.url).attr('alt', response.filename);
                editor.summernote('insertNode', uploadedImg[0]);
            } else {
                // Remove loading image and show error
                loadingImg.remove();
                alert('Image upload failed: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            // Remove loading image
            loadingImg.remove();
            let errorMsg = 'Upload failed. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            alert(errorMsg);
        }
    });
}

// Initialize when DOM is ready
$(document).ready(function() {
    console.log('Document ready, starting Summernote initialization');
    // Small delay to ensure all dependencies are loaded
    setTimeout(initializeSummernote, 500);
});

// Reinitialize when tab is shown (for Bootstrap tabs)
$(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function(e) {
    console.log('Tab shown, reinitializing Summernote');
    setTimeout(function() {
        // Only initialize if visible and not already initialized
        if ($('.summernote:visible').length > 0) {
            initializeSummernote();
        }
    }, 200);
});
</script>
