<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Check if the current user has permission to access the current route.
     * Applies role-based access control:
     * - guru: posts and galleries only
     * - operator: everything except users
     * - admin: everything
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('auth');

        // Check if user is logged in
        if (! logged_in()) {
            return redirect()->to('/login');
        }

        $user = current_user();
        $uri = service('uri');
        $segment2 = $uri->getSegment(2); // After 'dashboard'

        // Admin has full access
        if ($user->role === 'admin') {
            return;
        }

        // Guru only has access to posts and galleries
        if ($user->role === 'guru') {
            $allowedSegments = ['posts', 'galleries', 'media'];
            
            // Allow access to dashboard home page (segment2 is null or empty)
            if ($segment2 === null || $segment2 === '') {
                return;
            }

            // Allow access to API Media
            if ($segment2 === 'api' && $uri->getSegment(3) === 'media') {
                return;
            }
            
            // Check if accessing allowed segments
            if (!in_array($segment2, $allowedSegments)) {
                return redirect()->to('/dashboard')
                    ->with('error', 'You do not have permission to access this page.');
            }
            return;
        }

        // Operator has access to everything (including users)
        if ($user->role === 'operator') {
            return;
        }

        // Unknown role - deny access
        return redirect()->to('/dashboard')
            ->with('error', 'Invalid user role.');
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
