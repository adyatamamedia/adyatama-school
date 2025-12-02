<!-- Media Selection Modal Partial -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Box in Modal -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="modalMediaSearch" placeholder="Cari media...">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchModalMedia()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
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
    let allMediaData = []; // Store all media for filtering

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
        
        // Clear search box
        document.getElementById('modalMediaSearch').value = '';
        
        // Load media
        loadMediaGrid();
    });

    function loadMediaGrid(searchTerm = '') {
        console.log('Loading media from API...');
        fetch('<?= base_url('dashboard/api/media') ?>')
            .then(response => {
                console.log('API Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('API Data received:', data.length, 'items');
                allMediaData = data; // Store for filtering
                renderMediaGrid(data, searchTerm);
            })
            .catch(error => {
                console.error('Error loading media:', error);
                grid.innerHTML = '<p class="text-center w-100 text-danger">Error loading media!</p>';
            });
    }

    function renderMediaGrid(data, searchTerm = '') {
        console.log('Rendering media grid, data count:', data.length);
        grid.innerHTML = '';
        
        // Filter data if search term exists
        let filteredData = data;
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            filteredData = data.filter(item => 
                item.caption.toLowerCase().includes(term) ||
                item.path.toLowerCase().includes(term)
            );
        }
        
        console.log('Filtered data count:', filteredData.length);
        
        if(filteredData.length === 0) {
            grid.innerHTML = '<p class="text-center w-100">No media found.</p>';
            return;
        }
        
        filteredData.forEach((item, index) => {
            console.log('Rendering item', index, ':', item.path, 'id:', item.id);
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="card h-100 media-item" style="cursor: pointer;" data-path="${item.path}" data-id="${item.id}">
                    <img src="<?= base_url() ?>${item.path}" class="card-img-top" style="height: 100px; object-fit: cover;">
                    <div class="card-body p-1 text-center">
                        <small class="text-truncate d-block">${item.caption}</small>
                    </div>
                </div>
            `;
            
            // Add click event listener
            const cardEl = col.querySelector('.media-item');
            cardEl.addEventListener('click', function() {
                console.log('Image clicked! Path:', this.dataset.path, 'ID:', this.dataset.id);
                if (typeof window.selectMediaForInput === 'function') {
                    window.selectMediaForInput(this.dataset.path, parseInt(this.dataset.id));
                } else {
                    console.error('selectMediaForInput function not found!');
                }
            });
            
            grid.appendChild(col);
        });
        
        console.log('Media grid render complete');
    }

    // Search function for modal
    window.searchModalMedia = function() {
        const searchTerm = document.getElementById('modalMediaSearch').value;
        renderMediaGrid(allMediaData, searchTerm);
    };

    // Search on Enter key
    document.getElementById('modalMediaSearch').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchModalMedia();
        }
        // Real-time search while typing
        else {
            searchModalMedia();
        }
    });

    window.selectMediaForInput = function(path, id = null) {
        console.log('Default selectMediaForInput called - path:', path, 'id:', id);
        
        // Check if being called from Summernote editor
        if (window.currentSummernoteElement && window.currentSummernoteElement.length > 0) {
            console.log('Inserting image into Summernote editor');
            console.log('Editor element:', window.currentSummernoteElement);
            
            const fullUrl = '<?= base_url() ?>' + path;
            console.log('Full URL:', fullUrl);
            
            // Insert image using jQuery summernote API
            window.currentSummernoteElement.summernote('insertImage', fullUrl);
            
            console.log('Image inserted successfully');
            
            // Clear the reference
            window.currentSummernoteElement = null;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            return;
        }
        
        // Extract filename only (remove uploads/ prefix if exists)
        const filename = path.replace(/^\/?(uploads\/)?/, '');
        
        // Try to determine which inputs exist on the page
        
        // 1. Check for Pages/Staff/Galleries pattern: featured_image (hidden) + featured_image_display (text)
        const featuredImageHidden = document.getElementById('featured_image');
        const featuredImageDisplay = document.getElementById('featured_image_display');
        if(featuredImageHidden && featuredImageDisplay) {
            console.log('Pages/Staff/Galleries pattern detected');
            featuredImageHidden.value = path;
            featuredImageDisplay.value = path;
            
            // Show preview if function exists
            if(typeof showFeaturedImagePreview === 'function') {
                showFeaturedImagePreview(path);
            }
            
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            return;
        }
        
        // 2. Check for Posts pattern: featured_media_id (hidden) + featured_image_display (text)
        const featuredMediaIdHidden = document.getElementById('featured_media_id');
        const featuredMediaDisplay = document.getElementById('featured_image_display');
        if(featuredMediaIdHidden && featuredMediaDisplay && id) {
            console.log('Posts pattern detected');
            featuredMediaIdHidden.value = id;
            featuredMediaDisplay.value = path;
            
            // Show preview if function exists
            if(typeof showFeaturedImagePreview === 'function') {
                showFeaturedImagePreview(path);
            }
            
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            return;
        }
        
        // 3. Check for old "foto" pattern (Staff/Pages old version)
        const fotoInput = document.getElementById('foto');
        const fotoDisplay = document.getElementById('foto_display');
        if(fotoInput) {
            console.log('Foto pattern detected');
            fotoInput.value = filename;
            if(fotoDisplay) fotoDisplay.value = filename;
            
            // Show preview if function exists
            if(typeof showFotoPreview === 'function') {
                showFotoPreview(path);
            }
            
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            return;
        }
        
        console.warn('No matching input pattern found for media selection');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    };
});
</script>
