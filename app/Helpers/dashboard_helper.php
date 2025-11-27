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
     * Get formatted activity description
     */
    function getActivityDescription($log)
    {
        $descriptions = [
            // Auth
            'login' => 'Login ke sistem',
            'logout' => 'Logout dari sistem',

            // Post
            'create_post' => 'Membuat artikel baru',
            'update_post' => 'Mengupdate artikel',
            'delete_post' => 'Menghapus artikel',
            'publish_post' => 'Mempublikasi artikel',
            'draft_post' => 'Menyimpan artikel sebagai draft',

            // Page
            'create_page' => 'Membuat halaman baru',
            'update_page' => 'Mengupdate halaman',
            'delete_page' => 'Menghapus halaman',

            // Category
            'create_category' => 'Membuat kategori baru',
            'update_category' => 'Mengupdate kategori',
            'delete_category' => 'Menghapus kategori',

            // User
            'create_user' => 'Membuat user baru',
            'update_user' => 'Mengupdate data user',
            'delete_user' => 'Menghapus user',

            // Media
            'upload_media' => 'Upload file media',
            'delete_media' => 'Menghapus file media',
            'update_media' => 'Mengupdate file media',

            // Gallery
            'create_gallery' => 'Membuat galeri baru',
            'update_gallery' => 'Mengupdate galeri',
            'delete_gallery' => 'Menghapus galeri',

            // Settings
            'update_settings' => 'Mengupdate pengaturan sistem',

            // Other
            'student_application.created' => 'Pendaftaran siswa baru',
        ];

        $description = $descriptions[$log->action] ?? ucfirst(str_replace('_', ' ', $log->action));

        // Add meta info if available
        if ($log->meta) {
            $meta = json_decode($log->meta, true);
            if (isset($meta['nama_lengkap'])) {
                $description .= ': <strong>' . esc($meta['nama_lengkap']) . '</strong>';
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