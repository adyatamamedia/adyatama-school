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

        // Custom button to open media modal
        var MediaButton = function (context) {
            var ui = $.summernote.ui;

            // Create button
            var button = ui.button({
                contents: '<i class="fas fa-images"></i>',
                tooltip: 'Insert from Media Library',
                click: function () {
                    console.log('Media Library button clicked');
                    
                    // Store current editor element (not context)
                    window.currentSummernoteElement = $(context.layoutInfo.editable).closest('.note-editor').prev('.summernote');
                    
                    console.log('Stored editor element:', window.currentSummernoteElement);
                    
                    // Open media modal
                    var mediaModal = new bootstrap.Modal(document.getElementById('mediaModal'));
                    mediaModal.show();
                }
            });

            return button.render();
        };

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
                ['insert', ['link', 'mediaLibrary', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                mediaLibrary: MediaButton
            },
            callbacks: {
                onInit: function() {
                    console.log('✅ Summernote initialized successfully');
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
 * NOTE: This function is now deprecated. Images are inserted via Media Library modal.
 * Keeping this for backward compatibility if direct upload is needed in the future.
 */
function uploadSummernoteImage(file, editor) {
    console.log('Direct upload is disabled. Please use Media Library button.');
    alert('Please use the Media Library button in the toolbar to insert images.');
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
