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
        ];
    }
}

if (! function_exists('user_can')) {
    /**
     * Checks if the current user has a specific permission.
     * NOTE: Simplified for now (role-based check), will expand with permissions table later.
     */
    function user_can(string $permission): bool
    {
        if (! logged_in()) {
            return false;
        }

        $role = session('role');

        // Super admin bypass
        if ($role === 'admin') {
            return true;
        }

        // TODO: Implement real permission check against role_permissions table
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
