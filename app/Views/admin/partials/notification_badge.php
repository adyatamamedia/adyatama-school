<!-- Notification Badge -->
<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
            0
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationDropdownMenu" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        <li><h6 class="dropdown-header">Notifications</h6></li>
        <li><hr class="dropdown-divider"></li>
        <li id="notificationList">
            <div class="text-center text-muted p-3">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <div class="text-center">
                <a href="<?= base_url('dashboard/notifications') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                <button class="btn btn-sm btn-outline-secondary" onclick="markAllNotificationsAsRead()">Mark All Read</button>
            </div>
        </li>
    </ul>
</li>
