<?php

if (!function_exists('getActivityBadge')) {
    /**
     * Get badge color class for activity type
     */
    function getActivityBadge($action)
    {
        $badges = [
            // Auth
            'login' => 'success',
            'logout' => 'secondary',

            // Post
            'create_post' => 'primary',
            'update_post' => 'info',
            'delete_post' => 'danger',
            'publish_post' => 'success',
            'draft_post' => 'warning',

            // Page
            'create_page' => 'primary',
            'update_page' => 'info',
            'delete_page' => 'danger',

            // Category
            'create_category' => 'primary',
            'update_category' => 'info',
            'delete_category' => 'danger',

            // User
            'create_user' => 'primary',
            'update_user' => 'info',
            'delete_user' => 'danger',

            // Media
            'upload_media' => 'primary',
            'delete_media' => 'danger',
            'update_media' => 'info',

            // Gallery
            'create_gallery' => 'primary',
            'update_gallery' => 'info',
            'delete_gallery' => 'danger',

            // Settings
            'update_settings' => 'warning text-dark',

            // Other
            'student_application.created' => 'info',
        ];

        return $badges[$action] ?? 'secondary';
    }
}

if (!function_exists('getActivityIcon')) {
    /**
     * Get icon for activity type
     */
    function getActivityIcon($action)
    {
        $icons = [
            // Auth
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',

            // Post
            'create_post' => 'fas fa-plus-circle',
            'update_post' => 'fas fa-edit',
            'delete_post' => 'fas fa-trash',
            'publish_post' => 'fas fa-paper-plane',
            'draft_post' => 'fas fa-file',

            // Page
            'create_page' => 'fas fa-plus-circle',
            'update_page' => 'fas fa-edit',
            'delete_page' => 'fas fa-trash',

            // Category
            'create_category' => 'fas fa-plus-circle',
            'update_category' => 'fas fa-edit',
            'delete_category' => 'fas fa-trash',

            // User
            'create_user' => 'fas fa-user-plus',
            'update_user' => 'fas fa-user-edit',
            'delete_user' => 'fas fa-user-times',

            // Media
            'upload_media' => 'fas fa-cloud-upload-alt',
            'delete_media' => 'fas fa-trash',
            'update_media' => 'fas fa-edit',

            // Gallery
            'create_gallery' => 'fas fa-images',
            'update_gallery' => 'fas fa-edit',
            'delete_gallery' => 'fas fa-trash',

            // Settings
            'update_settings' => 'fas fa-cog',

            // Other
            'student_application.created' => 'fas fa-user-graduate',
        ];

        return $icons[$action] ?? 'fas fa-circle';
    }
}

if (!function_exists('getActivityDescription')) {
    /**
     * Get formatted activity description with title/name from metadata
     */
    function getActivityDescription($log)
    {
        $descriptions = [
            // Auth
            'login' => 'Login ke sistem',
            'logout' => 'Logout dari sistem',

            // Post
            'create_post' => 'Membuat artikel',
            'update_post' => 'Mengupdate artikel',
            'delete_post' => 'Menghapus artikel',
            'bulk_delete_post' => 'Menghapus artikel (bulk)',
            'bulk_draft_post' => 'Menyimpan artikel sebagai draft (bulk)',
            'bulk_publish_post' => 'Mempublikasi artikel (bulk)',
            'restore_post' => 'Restore artikel',
            'bulk_restore_post' => 'Restore artikel (bulk)',
            'bulk_force_delete_post' => 'Menghapus permanen artikel (bulk)',

            // Page
            'create_page' => 'Membuat halaman',
            'update_page' => 'Mengupdate halaman',
            'delete_page' => 'Menghapus halaman',
            'bulk_delete_page' => 'Menghapus halaman (bulk)',
            'bulk_draft_page' => 'Menyimpan halaman sebagai draft (bulk)',
            'bulk_publish_page' => 'Mempublikasi halaman (bulk)',

            // Category
            'create_category' => 'Membuat kategori',
            'update_category' => 'Mengupdate kategori',
            'delete_category' => 'Menghapus kategori',
            'bulk_delete_category' => 'Menghapus kategori (bulk)',

            // Tag
            'delete_tag' => 'Menghapus tag',
            'bulk_delete_tag' => 'Menghapus tag (bulk)',

            // User
            'create_user' => 'Membuat user',
            'update_user' => 'Mengupdate user',
            'delete_user' => 'Menghapus user',
            'bulk_delete_user' => 'Menghapus user (bulk)',

            // Media
            'upload_media' => 'Upload media',
            'update_media_caption' => 'Mengupdate caption media',
            'delete_media' => 'Menghapus media',
            'bulk_delete_media' => 'Menghapus media (bulk)',

            // Gallery
            'create_gallery' => 'Membuat galeri',
            'update_gallery' => 'Mengupdate galeri',
            'delete_gallery' => 'Menghapus galeri',
            'bulk_delete_gallery' => 'Menghapus galeri (bulk)',

            // Guru/Staff
            'create_guru_staff' => 'Menambah guru/staff',
            'update_guru_staff' => 'Mengupdate guru/staff',
            'delete_guru_staff' => 'Menghapus guru/staff',
            'bulk_delete_guru_staff' => 'Menghapus guru/staff (bulk)',

            // Extracurricular
            'create_extracurricular' => 'Membuat ekstrakurikuler',
            'update_extracurricular' => 'Mengupdate ekstrakurikuler',
            'delete_extracurricular' => 'Menghapus ekstrakurikuler',
            'bulk_delete_extracurricular' => 'Menghapus ekstrakurikuler (bulk)',

            // Student Application
            'update_student_application_status' => 'Update status pendaftaran siswa',
            'delete_student_application' => 'Menghapus pendaftaran siswa',

            // Subscriber
            'delete_subscriber' => 'Menghapus subscriber',
            'bulk_delete_subscriber' => 'Menghapus subscriber (bulk)',

            // Settings
            'update_settings' => 'Mengupdate pengaturan sistem',

            // Other
            'student_application.created' => 'Pendaftaran siswa baru',
        ];

        $description = $descriptions[$log->action] ?? ucfirst(str_replace('_', ' ', $log->action));

        // Add title/name from metadata
        if ($log->meta) {
            $meta = json_decode($log->meta, true);
            
            // Extract title/name based on available keys in metadata
            $displayName = null;
            
            if (isset($meta['title'])) {
                $displayName = $meta['title'];
            } elseif (isset($meta['name'])) {
                $displayName = $meta['name'];
            } elseif (isset($meta['nama'])) {
                $displayName = $meta['nama'];
            } elseif (isset($meta['username'])) {
                $displayName = $meta['username'];
            } elseif (isset($meta['email'])) {
                $displayName = $meta['email'];
            } elseif (isset($meta['filename'])) {
                $displayName = $meta['filename'];
            } elseif (isset($meta['slug'])) {
                $displayName = $meta['slug'];
            } elseif (isset($meta['caption'])) {
                $displayName = $meta['caption'];
            }
            
            if ($displayName) {
                $description .= ': <strong class="text-primary">' . esc($displayName) . '</strong>';
            }
            
            // Add additional info (role, status, etc)
            if (isset($meta['role']) && $meta['role']) {
                $description .= ' <span class="badge bg-info">' . esc($meta['role']) . '</span>';
            }
            if (isset($meta['status']) && $meta['status']) {
                $description .= ' <span class="badge bg-warning text-dark">' . esc($meta['status']) . '</span>';
            }
        }

        return $description;
    }
}

if (!function_exists('time_ago')) {
    /**
     * Convert datetime to "time ago" format
     */
    function time_ago($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' menit yang lalu';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' jam yang lalu';
        } else {
            return floor($diff / 86400) . ' hari yang lalu';
        }
    }
}

if (!function_exists('format_number')) {
    /**
     * Format number with thousands separator
     */
    function format_number($number)
    {
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('get_trend_indicator')) {
    /**
     * Get trend indicator for growth percentage
     */
    function get_trend_indicator($percentage)
    {
        if ($percentage > 0) {
            return '<span class="metric-badge metric-positive"><i class="fas fa-arrow-up me-1"></i>' . abs($percentage) . '%</span>';
        } elseif ($percentage < 0) {
            return '<span class="metric-badge metric-negative"><i class="fas fa-arrow-down me-1"></i>' . abs($percentage) . '%</span>';
        } else {
            return '<span class="metric-badge metric-neutral"><i class="fas fa-minus me-1"></i>0%</span>';
        }
    }
}

if (!function_exists('nav_active')) {
    /**
     * Check if current URL matches patterns
     * 
     * @param string|array $patterns Patterns to match
     * @param string $mode 'contains' or 'exact'
     * @return string 'active' or ''
     */
    function nav_active($patterns, $mode = 'contains')
    {
        $uri = uri_string();
        $patterns = is_array($patterns) ? $patterns : [$patterns];

        foreach ($patterns as $pattern) {
            if ($mode === 'exact') {
                if ($uri === $pattern) return 'active';
            } else {
                if (strpos($uri, $pattern) !== false) return 'active';
            }
        }
        return '';
    }
}

if (!function_exists('nav_menu_open')) {
    /**
     * Check if menu should be open
     * 
     * @param string|array $patterns Patterns to match
     * @return string 'menu-open' or ''
     */
    function nav_menu_open($patterns) {
        return nav_active($patterns) !== '' ? 'menu-open' : '';
    }
}