<!-- Media Selection Modal Partial -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mediaGrid" class="row row-cols-4 g-3">
                    <!-- Loaded via AJAX -->
                    <p class="text-center w-100">Loading media...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('mediaModal');
    const grid = document.getElementById('mediaGrid');
    let targetInputId = null;

    // Detect which button opened the modal
    modalEl.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extract info from data-* attributes if needed, or just infer
        // For now, we assume if it's opened from "selectPhotoBtn", target is "foto"
        if(button && button.id === 'selectPhotoBtn') {
            targetInputId = 'foto';
        } else if(button && button.id === 'selectImageBtn') {
            targetInputId = 'featured_media_id'; // Correct ID for posts
        } else {
            targetInputId = null;
        }
        
        // Load media
        fetch('<?= base_url('dashboard/api/media') ?>')
            .then(response => response.json())
            .then(data => {
                grid.innerHTML = '';
                if(data.length === 0) {
                    grid.innerHTML = '<p class="text-center w-100">No media found. Upload files in Media Library first.</p>';
                    return;
                }
                
                data.forEach(item => {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 cursor-pointer media-item" onclick="selectMediaForInput('${item.path}', ${item.id})">
                            <img src="<?= base_url() ?>${item.path}" class="card-img-top" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-1 text-center">
                                <small class="text-truncate d-block">${item.caption}</small>
                            </div>
                        </div>
                    `;
                    grid.appendChild(col);
                });
            });
    });

    window.selectMediaForInput = function(path, id = null) {
        // If we set targetInputId dynamically
        if(targetInputId) {
             // If selecting for featured image logic which usually needs ID
             if(targetInputId === 'featured_media_id' && id) {
                 const hiddenInput = document.getElementById('featured_media_id');
                 const displayInput = document.getElementById('featured_image_display');
                 if(hiddenInput) hiddenInput.value = id;
                 if(displayInput) displayInput.value = path;
             } else {
                 const input = document.getElementById(targetInputId);
                 if(input) input.value = path;
             }
        } else {
             // Default Fallback Logic
             
             // 1. Check for "foto" input (String path) - used in Staff/Pages
             const fotoInput = document.getElementById('foto');
             if(fotoInput) fotoInput.value = path;
             
             // 2. Check for "featured_media_id" (Int ID) - used in Posts
             if(id) {
                 const featIdInput = document.getElementById('featured_media_id');
                 const featDisplay = document.getElementById('featured_image_display');
                 if(featIdInput) featIdInput.value = id;
                 if(featDisplay) featDisplay.value = path;
             }
        }

        // Close modal
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    };
});
</script>
