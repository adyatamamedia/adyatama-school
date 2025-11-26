<!-- Gallery Items Selection Modal -->
<div class="modal fade" id="galleryItemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Gallery Items (Multiple)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Box -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="galleryItemsSearch" placeholder="Search media...">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchGalleryItems()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Tip:</strong> Klik untuk select/unselect, atau Click & Drag untuk select area
                </div>
                
                <!-- Media Grid -->
                <div id="galleryItemsGrid" class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3" style="user-select: none;">
                    <!-- Loaded via JS -->
                    <p class="text-center w-100">Loading media...</p>
                </div>
            </div>
            <div class="modal-footer">
                <span class="me-auto text-muted" id="modalSelectedCount">0 selected</span>
                <button type="button" class="btn btn-secondary" onclick="clearGallerySelection()">
                    <i class="fas fa-times me-1"></i>Clear All
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmGallerySelection()">
                    <i class="fas fa-check me-1"></i>Confirm Selection
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.gallery-item-selectable {
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.gallery-item-selectable:hover {
    transform: scale(1.05);
}

.gallery-item-selectable.selected {
    outline: 3px solid #0d6efd;
    outline-offset: 2px;
}

.gallery-item-selectable.selected::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 5px;
    right: 5px;
    background: #0d6efd;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.gallery-item-selectable img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 4px;
}
</style>

<script>
// Gallery Items Selection State
let galleryItemsData = [];
let selectedGalleryItems = new Set();
let isDragging = false;
let dragStartItem = null;
let mouseHasMoved = false;
let mouseDownTime = 0;

// Load media when modal opens
document.getElementById('galleryItemsModal').addEventListener('show.bs.modal', function() {
    loadGalleryItemsMedia();
});

function loadGalleryItemsMedia(searchTerm = '') {
    console.log('Loading media for gallery items...');
    fetch('<?= base_url('dashboard/api/media') ?>')
        .then(response => response.json())
        .then(data => {
            console.log('Gallery Items - Media loaded:', data.length, 'items');
            galleryItemsData = data;
            renderGalleryItemsGrid(data, searchTerm);
        })
        .catch(error => {
            console.error('Error loading media:', error);
            document.getElementById('galleryItemsGrid').innerHTML = '<p class="text-center w-100 text-danger">Error loading media!</p>';
        });
}

function renderGalleryItemsGrid(data, searchTerm = '') {
    const grid = document.getElementById('galleryItemsGrid');
    grid.innerHTML = '';
    
    // Filter data
    let filteredData = data;
    if (searchTerm) {
        const term = searchTerm.toLowerCase();
        filteredData = data.filter(item => 
            item.caption.toLowerCase().includes(term) ||
            item.path.toLowerCase().includes(term)
        );
    }
    
    if (filteredData.length === 0) {
        grid.innerHTML = '<p class="text-center w-100">No media found.</p>';
        return;
    }
    
    filteredData.forEach((item, index) => {
        const col = document.createElement('div');
        col.className = 'col';
        
        const isSelected = selectedGalleryItems.has(item.id);
        
        col.innerHTML = `
            <div class="gallery-item-selectable ${isSelected ? 'selected' : ''}" 
                 data-id="${item.id}" 
                 data-path="${item.path}"
                 data-index="${index}">
                <img src="<?= base_url() ?>${item.path}" alt="${item.caption}">
                <small class="d-block text-center text-truncate mt-1">${item.caption}</small>
            </div>
        `;
        
        grid.appendChild(col);
        
        const itemEl = col.querySelector('.gallery-item-selectable');
        
        // Mousedown - Start tracking
        itemEl.addEventListener('mousedown', function(e) {
            e.preventDefault();
            mouseDownTime = Date.now();
            mouseHasMoved = false;
            dragStartItem = item.id;
        });
        
        // Mousemove - Detect if dragging
        itemEl.addEventListener('mousemove', function(e) {
            if (dragStartItem && !mouseHasMoved) {
                // User is moving mouse = dragging intent
                mouseHasMoved = true;
                isDragging = true;
            }
        });
        
        // Click event - Only if NOT dragging
        itemEl.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const clickDuration = Date.now() - mouseDownTime;
            
            // If mouse moved or took too long, ignore click (was a drag)
            if (mouseHasMoved || clickDuration > 300) {
                console.log('Ignored click (was drag)');
                return;
            }
            
            console.log('Click detected - toggling', item.id);
            
            // Toggle selection
            if (selectedGalleryItems.has(item.id)) {
                selectedGalleryItems.delete(item.id);
                itemEl.classList.remove('selected');
            } else {
                selectedGalleryItems.add(item.id);
                itemEl.classList.add('selected');
            }
            
            updateModalSelectedCount();
        });
        
        // Mouse enter for drag select
        itemEl.addEventListener('mouseenter', function(e) {
            if (isDragging && !selectedGalleryItems.has(item.id)) {
                console.log('Drag selecting', item.id);
                selectedGalleryItems.add(item.id);
                itemEl.classList.add('selected');
                updateModalSelectedCount();
            }
        });
    });
    
    // Mouse up anywhere to stop dragging and reset flags
    const mouseUpHandler = function() {
        if (isDragging) {
            console.log('Drag ended');
        }
        isDragging = false;
        dragStartItem = null;
        mouseHasMoved = false;
    };
    
    // Remove old listener if exists and add new one
    document.removeEventListener('mouseup', mouseUpHandler);
    document.addEventListener('mouseup', mouseUpHandler);
    
    updateModalSelectedCount();
}

function toggleItemSelection(itemId) {
    // Simple toggle
    if (selectedGalleryItems.has(itemId)) {
        selectedGalleryItems.delete(itemId);
    } else {
        selectedGalleryItems.add(itemId);
    }
    
    // Update UI
    const itemEl = document.querySelector(`.gallery-item-selectable[data-id="${itemId}"]`);
    if (itemEl) {
        if (selectedGalleryItems.has(itemId)) {
            itemEl.classList.add('selected');
        } else {
            itemEl.classList.remove('selected');
        }
    }
    
    updateModalSelectedCount();
}

function updateModalSelectedCount() {
    document.getElementById('modalSelectedCount').textContent = `${selectedGalleryItems.size} selected`;
}

function clearGallerySelection() {
    selectedGalleryItems.clear();
    document.querySelectorAll('.gallery-item-selectable.selected').forEach(el => {
        el.classList.remove('selected');
    });
    updateModalSelectedCount();
}

function confirmGallerySelection() {
    console.log('Confirming selection:', Array.from(selectedGalleryItems));
    
    // Update preview
    updateGalleryItemsPreview();
    
    // Update hidden inputs
    updateGalleryItemsInputs();
    
    // Update counter badge
    document.getElementById('selectedCount').textContent = `${selectedGalleryItems.size} selected`;
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('galleryItemsModal'));
    modal.hide();
}

function updateGalleryItemsPreview() {
    const preview = document.getElementById('galleryItemsPreview');
    preview.innerHTML = '';
    
    if (selectedGalleryItems.size === 0) {
        preview.style.display = 'none';
        return;
    }
    
    preview.style.display = 'flex';
    
    selectedGalleryItems.forEach(itemId => {
        const itemData = galleryItemsData.find(item => item.id === itemId);
        if (!itemData) return;
        
        const col = document.createElement('div');
        col.className = 'col position-relative';
        
        const img = document.createElement('img');
        img.src = '<?= base_url() ?>' + itemData.path;
        img.className = 'img-fluid rounded';
        img.style.cssText = 'height: 100px; object-fit: cover;';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
        removeBtn.style.cssText = 'padding: 2px 6px; font-size: 10px;';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        
        // Add click event - Use closure to capture itemId
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Remove button clicked for item:', itemId);
            removeGalleryItem(itemId);
        });
        
        col.appendChild(img);
        col.appendChild(removeBtn);
        preview.appendChild(col);
    });
}

function updateGalleryItemsInputs() {
    const container = document.getElementById('galleryItemsInputs');
    container.innerHTML = '';
    
    selectedGalleryItems.forEach(itemId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'gallery_items[]';
        input.value = itemId;
        container.appendChild(input);
    });
}

function removeGalleryItem(itemId) {
    console.log('Removing item:', itemId);
    selectedGalleryItems.delete(itemId);
    
    // Update modal UI if modal is open
    const itemEl = document.querySelector(`.gallery-item-selectable[data-id="${itemId}"]`);
    if (itemEl) {
        itemEl.classList.remove('selected');
    }
    
    // Update preview and inputs
    updateGalleryItemsPreview();
    updateGalleryItemsInputs();
    
    // Update counters
    document.getElementById('selectedCount').textContent = `${selectedGalleryItems.size} selected`;
    updateModalSelectedCount();
    
    console.log('Item removed. Remaining:', selectedGalleryItems.size);
}

function searchGalleryItems() {
    const searchTerm = document.getElementById('galleryItemsSearch').value;
    renderGalleryItemsGrid(galleryItemsData, searchTerm);
}

// Search on Enter key
document.getElementById('galleryItemsSearch').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        searchGalleryItems();
    }
});
</script>
