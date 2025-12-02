<?php helper('auth'); ?>
<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start Navbar Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="<?= base_url() ?>" class="nav-link" target="_blank">View Site</a>
            </li>
        </ul>

        <!-- End Navbar Links -->
        <ul class="navbar-nav ms-auto">
            <!-- Notifications -->
            <?php 
            $notificationsCount = get_notifications_count();
            $recentNotifications = get_recent_notifications(5);
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($notificationsCount > 0): ?>
                        <span class="navbar-badge badge badge-danger"><?= $notificationsCount ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header"><?= $notificationsCount ?> Notification<?= $notificationsCount != 1 ? 's' : '' ?></span>
                    
                    <?php if (!empty($recentNotifications)): ?>
                        <div class="dropdown-divider"></div>
                        <?php foreach ($recentNotifications as $notification): ?>
                            <a href="<?= $notification->url ?>" class="dropdown-item">
                                <i class="<?= $notification->icon ?> mr-2 text-<?= $notification->color ?>"></i>
                                <span class="text-sm">
                                    <strong><?= esc($notification->title) ?></strong><br>
                                    <small class="text-muted"><?= esc($notification->message) ?></small>
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach; ?>
                        <a href="<?= base_url('dashboard/activity-logs') ?>" class="dropdown-item dropdown-footer">See All Notifications</a>
                    <?php else: ?>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-item text-center text-muted">No new notifications</span>
                    <?php endif; ?>
                </div>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link user-menu-link" data-bs-toggle="dropdown">
                    <img src="<?= get_user_avatar() ?>" class="user-image rounded-circle" alt="User Image">
                    <span class="d-none d-md-inline fw-medium"><?= esc(current_user()->fullname ?? 'User') ?></span>
                    <i class="fas fa-chevron-down d-none d-md-inline" style="font-size: 0.75rem; opacity: 0.7;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-modern dropdown-menu-end">
                    <!-- User Info Header -->
                    <div class="user-dropdown-header">
                        <div class="user-avatar-wrapper">
                            <img src="<?= get_user_avatar() ?>" class="user-avatar" alt="User Image">
                            <div class="user-status-indicator"></div>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?= esc(current_user()->fullname ?? 'User') ?></div>
                            <div class="user-role">
                                <i class="fas fa-shield-alt me-1"></i>
                                <?= esc(ucfirst(current_user()->role ?? 'Guest')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <!-- Menu Items -->
                    <a href="#" class="dropdown-item-modern">
                        <div class="dropdown-item-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">My Profile</div>
                            <div class="dropdown-item-subtitle">View and edit profile</div>
                        </div>
                    </a>
                    
                    <a href="<?= base_url('dashboard/settings') ?>" class="dropdown-item-modern">
                        <div class="dropdown-item-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Settings</div>
                            <div class="dropdown-item-subtitle">Manage preferences</div>
                        </div>
                    </a>
                    
                    <a href="<?= base_url('dashboard/activity-logs') ?>" class="dropdown-item-modern">
                        <div class="dropdown-item-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Activity Log</div>
                            <div class="dropdown-item-subtitle">Recent activities</div>
                        </div>
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <!-- Logout Button -->
                    <a href="<?= base_url('logout') ?>" class="dropdown-item-modern logout-item">
                        <div class="dropdown-item-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Sign Out</div>
                            <div class="dropdown-item-subtitle">Logout from account</div>
                        </div>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
/* ===== Modern Navbar Dropdown Styles ===== */

/* Notification Badge */
.navbar-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.625rem;
    font-weight: 700;
    padding: 3px 6px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    animation: pulse-badge 2s infinite;
}

@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.nav-link {
    position: relative;
    display: flex !important;
    align-items: center !important;
    transition: all 0.3s ease;
}

/* Force remove any img spacing in nav-link */
.nav-link img {
    margin: 0 !important;
    padding: 0 !important;
    vertical-align: middle !important;
}

.user-menu-link {
    padding: 0.5rem 1rem !important;
    border-radius: 50px;
    transition: all 0.3s ease;
    line-height: 1 !important; /* Remove extra line-height spacing */
    display: flex !important; /* Force flex display */
    align-items: center !important; /* Force center alignment */
    gap: 0.5rem !important; /* Consistent spacing */
}

/* Keep active state when dropdown is open */
.user-menu.show .user-menu-link,
.user-menu-link:hover {
    background-color: rgba(102, 126, 234, 0.1);
}

/* User Image in Navbar - Force Perfect Centering */
.user-image {
    width: 35px !important;
    height: 35px !important;
    object-fit: cover;
    border: 2px solid rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    display: block !important; /* Prevent inline spacing issues */
    flex-shrink: 0; /* Prevent flex shrinking */
    margin: 0 !important; /* Remove any default margin */
    padding: 0 !important; /* Remove any default padding */
    vertical-align: middle !important; /* Force vertical align */
    line-height: 0 !important; /* Remove line-height spacing */
}

.user-menu.show .user-menu-link .user-image,
.user-menu-link:hover .user-image {
    border-color: rgba(102, 126, 234, 0.6);
    transform: scale(1.05);
}

/* ===== Modern Dropdown Menu ===== */
.dropdown-menu-modern {
    min-width: 320px;
    border: none;
    border-radius: 16px;
    padding: 0;
    margin-top: 0.75rem;
    background: white;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    animation: dropdownSlideIn 0.3s ease;
    overflow: hidden;
}

@keyframes dropdownSlideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* User Dropdown Header with Gradient */
.user-dropdown-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}

.user-dropdown-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(180deg); }
}

.user-avatar-wrapper {
    position: relative;
    z-index: 1;
}

.user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.user-status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.user-info {
    flex: 1;
    z-index: 1;
}

.user-name {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-role {
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Modern Dropdown Items */
.dropdown-item-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border: none;
    background: transparent;
    transition: all 0.25s ease;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.dropdown-item-modern::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scaleY(0);
    transition: transform 0.25s ease;
}

.dropdown-item-modern:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, transparent 100%);
    transform: translateX(3px);
}

.dropdown-item-modern:hover::before {
    transform: scaleY(1);
}

.dropdown-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
    font-size: 1rem;
    transition: all 0.25s ease;
}

.dropdown-item-modern:hover .dropdown-item-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.dropdown-item-content {
    flex: 1;
}

.dropdown-item-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.125rem;
}

.dropdown-item-subtitle {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Logout Item Special Styling */
.logout-item .dropdown-item-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.logout-item:hover .dropdown-item-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.logout-item:hover {
    background: linear-gradient(90deg, rgba(239, 68, 68, 0.05) 0%, transparent 100%);
}

/* Dropdown Divider */
.dropdown-menu-modern .dropdown-divider {
    margin: 0.5rem 0;
    border-color: rgba(0, 0, 0, 0.06);
}

/* ===== Notification Dropdown Styles ===== */
.dropdown-menu-lg {
    min-width: 350px;
    max-width: 400px;
    border: none;
    border-radius: 16px;
    padding: 0;
    margin-top: 0.75rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    animation: dropdownSlideIn 0.3s ease;
}

.dropdown-header {
    font-weight: 600;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    color: #667eea;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    font-size: 0.875rem;
}

.dropdown-item {
    padding: 0.875rem 1.25rem;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.dropdown-item:hover {
    background: rgba(102, 126, 234, 0.05);
    border-left-color: #667eea;
    transform: translateX(3px);
}

.dropdown-item i {
    width: 24px;
    text-align: center;
    font-size: 1rem;
}

.dropdown-footer {
    text-align: center;
    font-weight: 600;
    padding: 1rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
    font-size: 0.875rem;
    color: #667eea;
}

.dropdown-footer:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #764ba2;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .dropdown-menu-modern {
        min-width: 280px;
    }
    
    .user-dropdown-header {
        padding: 1.25rem 1rem;
    }
    
    .dropdown-item-modern {
        padding: 0.875rem 1rem;
    }
}

/* Smooth Scrollbar for Dropdowns */
.dropdown-menu-modern,
.dropdown-menu-lg {
    max-height: 80vh;
    overflow-y: auto;
}

.dropdown-menu-modern::-webkit-scrollbar,
.dropdown-menu-lg::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu-modern::-webkit-scrollbar-track,
.dropdown-menu-lg::-webkit-scrollbar-track {
    background: transparent;
}

.dropdown-menu-modern::-webkit-scrollbar-thumb,
.dropdown-menu-lg::-webkit-scrollbar-thumb {
    background: rgba(102, 126, 234, 0.3);
    border-radius: 3px;
}

.dropdown-menu-modern::-webkit-scrollbar-thumb:hover,
.dropdown-menu-lg::-webkit-scrollbar-thumb:hover {
    background: rgba(102, 126, 234, 0.5);
}
</style>
