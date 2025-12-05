<!-- Notification Dropdown Menu -->
<div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationDropdownMenu" style="min-width: 350px; max-height: 450px; overflow-y: auto;">
    <li><h6 class="dropdown-header d-flex justify-content-between align-items-center">
        <span>Notifications</span>
        <small class="text-muted" id="notificationTime"></small>
    </h6></li>
    <li><hr class="dropdown-divider"></li>
    <li id="notificationList">
        <!-- Notifications will be loaded here via JavaScript -->
    </li>
    <li><hr class="dropdown-divider"></li>
    <li class="px-3 pb-2">
        <div class="d-flex justify-content-between">
            <a href="<?= base_url('dashboard/notifications') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            <button class="btn btn-sm btn-outline-secondary" onclick="markAllNotificationsAsRead()">Mark All Read</button>
        </div>
    </li>
</div>

<script>
// Function to mark all notifications as read
function markAllNotificationsAsRead() {
    fetch('<?= base_url('dashboard/notifications/mark-all-read') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update notification list
            loadNotifications();
            // Update badge
            updateNotificationBadge(0);
            
            // Show success message
            showToast('All notifications marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error marking notifications as read', 'error');
    });
}

// Function to load notifications
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    if (!notificationList) return;
    
    notificationList.innerHTML = '<div class="px-3 py-2"><div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div></div>';
    
    fetch('<?= base_url('dashboard/notifications') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.notifications && data.notifications.length > 0) {
            let html = '';
            data.notifications.forEach(notification => {
                const iconClass = getNotificationIcon(notification.type);
                const textClass = notification.is_read ? 'text-muted' : '';
                const bgClass = getNotificationBg(notification.type);
                
                html += `
                    <a href="#" class="dropdown-item d-flex align-items-start ${notification.is_read ? '' : 'bg-light'} ${textClass}" onclick="markNotificationAsRead(${notification.id}); return false;">
                        <div class="me-2">
                            <i class="fas ${iconClass} ${bgClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong class="${notification.is_read ? 'text-muted' : ''}">${notification.title}</strong>
                                <small class="text-muted">${formatTime(notification.created_at)}</small>
                            </div>
                            <div class="${textClass} small">${notification.message}</div>
                            ${notification.module ? `<small class="badge bg-secondary">${notification.module}</small>` : ''}
                            ${notification.version ? `<small class="badge bg-info">${notification.version}</small>` : ''}
                        </div>
                    </a>
                `;
            });
            
            notificationList.innerHTML = html;
        } else {
            notificationList.innerHTML = '<div class="px-3 py-2"><div class="text-center text-muted">No notifications</div></div>';
        }
        
        // Update notification time
        updateNotificationTime();
    })
    .catch(error => {
        console.error('Error loading notifications:', error);
        notificationList.innerHTML = '<div class="px-3 py-2"><div class="text-center text-danger">Error loading notifications</div></div>';
    });
}

// Function to get notification icon based on type
function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'fa-check-circle';
        case 'warning': return 'fa-exclamation-triangle';
        case 'error': return 'fa-times-circle';
        default: return 'fa-info-circle';
    }
}

// Function to get notification background color based on type
function getNotificationBg(type) {
    switch (type) {
        case 'success': return 'text-success';
        case 'warning': return 'text-warning';
        case 'error': return 'text-danger';
        default: return 'text-primary';
    }
}

// Function to format time
function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    
    if (diffMs < 60000) { // Less than 1 minute
        return 'Just now';
    } else if (diffMs < 3600000) { // Less than 1 hour
        const minutes = Math.floor(diffMs / 60000);
        return `${minutes}m ago`;
    } else if (diffMs < 86400000) { // Less than 1 day
        const hours = Math.floor(diffMs / 3600000);
        return `${hours}h ago`;
    } else {
        return date.toLocaleDateString();
    }
}

// Function to update notification time display
function updateNotificationTime() {
    const timeElement = document.getElementById('notificationTime');
    if (timeElement) {
        timeElement.textContent = new Date().toLocaleTimeString();
    }
}

// Function to mark single notification as read
function markNotificationAsRead(id) {
    fetch(`<?= base_url('dashboard/notifications/mark-read/') ?>${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update notification list
            loadNotifications();
            // Update badge count
            updateNotificationBadge(parseInt(document.getElementById('notificationBadge').textContent) - 1);
            
            // Show success message
            showToast('Notification marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error marking notification as read', 'error');
    });
}

// Function to update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (!badge) return;
    
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

// Function to show toast messages
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastHtml;
    toastContainer.appendChild(toastElement);
    
    const toast = new bootstrap.Toast(toastElement.querySelector('.toast'));
    toast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}
</script>
