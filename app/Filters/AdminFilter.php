<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Check if the current user has admin role.
     * Redirect to dashboard if not admin.
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

        // Check if user has admin or operator role
        $user = current_user();
        if (!$user || !in_array($user->role, ['admin', 'operator'])) {
            return redirect()->to('/dashboard')
                ->with('error', 'You do not have permission to access this page. Only administrators and operators can manage users.');
        }
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
