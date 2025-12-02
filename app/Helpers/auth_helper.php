<?php

if (! function_exists('logged_in')) {
    /**
     * Checks if the user is logged in.
     */
    function logged_in(): bool
    {
        return session()->has('user_id');
    }
}

if (! function_exists('current_user')) {
    /**
     * Returns the currently logged in user data or null.
     */
    function current_user()
    {
        if (! logged_in()) {
            return null;
        }
        
        // Basic session caching could be implemented here
        // For now, let's trust the session data or fetch fresh if needed
        // But usually we store minimal info in session
        return (object) [
            'id' => session('user_id'),
            'username' => session('username'),
            'role' => session('role'),
            'fullname' => session('fullname'),
            'photo' => session('photo'),
        ];
    }
}

if (! function_exists('user_can')) {
    /**
     * Checks if the current user has a specific permission.
     * Role-based access control:
     * - admin: full access
     * - operator: full access except user management
     * - guru: posts and galleries only
     */
    function user_can(string $permission): bool
    {
        if (! logged_in()) {
            return false;
        }

        $role = session('role');

        // Admin has full access
        if ($role === 'admin') {
            return true;
        }

        // Operator has full access (can manage users but with restrictions)
        if ($role === 'operator') {
            return true;
        }

        // Guru only has access to posts and galleries
        if ($role === 'guru') {
            $allowedPermissions = ['manage_posts', 'manage_galleries'];
            return in_array($permission, $allowedPermissions);
        }

        return false; 
    }
}

if (! function_exists('can_access_menu')) {
    /**
     * Check if user can access a specific menu
     * 
     * @param string $menu Menu identifier
     * @return bool
     */
    function can_access_menu(string $menu): bool
    {
        if (! logged_in()) {
            return false;
        }

        $role = session('role');

        // Admin has access to everything
        if ($role === 'admin') {
            return true;
        }

        // Operator has access to everything (including users)
        if ($role === 'operator') {
            return true;
        }

        // Guru only has access to posts and galleries (and media for uploading)
        if ($role === 'guru') {
            $allowedMenus = ['posts', 'galleries', 'media'];
            return in_array($menu, $allowedMenus);
        }

        return false;
    }
}

if (! function_exists('setting')) {
    /**
     * Get system setting value by key.
     * Uses simple caching to avoid repeated DB calls in same request.
     */
    function setting($key, $default = null)
    {
        static $settingsCache = null;

        if ($settingsCache === null) {
            $db = \Config\Database::connect();
            // Check if table exists to avoid errors during migration/setup
            if ($db->tableExists('settings')) {
                $query = $db->table('settings')->get();
                foreach ($query->getResult() as $row) {
                    $settingsCache[$row->key_name] = $row->value;
                }
            } else {
                $settingsCache = [];
            }
        }

        return $settingsCache[$key] ?? $default;
    }
}

if (! function_exists('log_activity')) {
    /**
     * Log user activity to database.
     * 
     * @param string $action Action name (e.g., 'login', 'create_post')
     * @param string|null $subjectType (e.g., 'post', 'user')
     * @param int|null $subjectId
     * @param array|null $meta Additional data
     */
    function log_activity($action, $subjectType = null, $subjectId = null, $meta = null)
    {
        $logModel = new \App\Models\ActivityLogModel();
        $request = \Config\Services::request();
        
        $logModel->save([
            'user_id'      => session('user_id'),
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'ip_address'   => $request->getIPAddress(),
            'user_agent'   => $request->getUserAgent()->getAgentString(),
            'meta'         => $meta ? json_encode($meta) : null,
        ]);
    }
}

if (! function_exists('get_user_avatar')) {
    /**
     * Get user avatar URL with fallback to generated avatar.
     * 
     * @param object|null $user User object (uses current_user if null)
     * @return string Avatar URL
     */
    function get_user_avatar($user = null)
    {
        if ($user === null) {
            $user = current_user();
        }

        if (!$user) {
            return 'https://ui-avatars.com/api/?name=Guest&background=random';
        }

        // Check if user has uploaded photo
        if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
            return base_url($user->photo);
        }

        // Fallback to generated avatar
        $name = $user->fullname ?? $user->username ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random';
    }
}

if (! function_exists('get_notifications_count')) {
    /**
     * Get total unread notifications count.
     * 
     * @return int Total count
     */
    function get_notifications_count()
    {
        $db = \Config\Database::connect();
        $count = 0;

        // Count pending comments
        if ($db->tableExists('comments')) {
            $count += $db->table('comments')
                ->where('is_approved', 0)
                ->where('is_spam', 0)
                ->countAllResults();
        }

        // Count pending student applications
        if ($db->tableExists('student_applications')) {
            $count += $db->table('student_applications')
                ->where('status', 'pending')
                ->countAllResults();
        }

        return $count;
    }
}

if (! function_exists('get_recent_notifications')) {
    /**
     * Get recent notifications (pending comments & applications).
     * 
     * @param int $limit Number of notifications to return
     * @return array Array of notification objects
     */
    function get_recent_notifications($limit = 5)
    {
        $db = \Config\Database::connect();
        $notifications = [];

        // Get pending comments
        if ($db->tableExists('comments')) {
            $comments = $db->table('comments')
                ->select('comments.*, posts.title as post_title')
                ->join('posts', 'posts.id = comments.post_id', 'left')
                ->where('comments.is_approved', 0)
                ->where('comments.is_spam', 0)
                ->orderBy('comments.created_at', 'DESC')
                ->limit($limit)
                ->get()
                ->getResult();

            foreach ($comments as $comment) {
                $notifications[] = (object)[
                    'id' => $comment->id,
                    'type' => 'comment',
                    'title' => 'New Comment',
                    'message' => substr($comment->content, 0, 50) . '...',
                    'author' => $comment->author_name ?? 'Anonymous',
                    'post_title' => $comment->post_title ?? 'Unknown Post',
                    'url' => base_url('dashboard/comments'),
                    'icon' => 'fas fa-comment',
                    'color' => 'info',
                    'created_at' => $comment->created_at,
                ];
            }
        }

        // Get pending student applications
        if ($db->tableExists('student_applications')) {
            $applications = $db->table('student_applications')
                ->where('status', 'pending')
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get()
                ->getResult();

            foreach ($applications as $app) {
                $notifications[] = (object)[
                    'id' => $app->id,
                    'type' => 'application',
                    'title' => 'New Student Application',
                    'message' => 'Application from ' . $app->nama_lengkap,
                    'author' => $app->nama_lengkap,
                    'post_title' => '',
                    'url' => base_url('dashboard/pendaftaran'),
                    'icon' => 'fas fa-user-plus',
                    'color' => 'success',
                    'created_at' => $app->created_at,
                ];
            }
        }

        // Sort by created_at and limit
        usort($notifications, function($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return array_slice($notifications, 0, $limit);
    }
}
