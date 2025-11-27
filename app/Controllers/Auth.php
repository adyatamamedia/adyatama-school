<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        helper('auth');
        if (logged_in()) {
            return redirect()->to('/dashboard');
        }
        
        helper(['form']);
        return view('auth/login');
    }

    public function attemptLogin()
    {
        helper('auth'); // Ensure helper is loaded
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Check password
            if (password_verify($password, $user->password_hash)) {
                // Check status
                if ($user->status !== 'active') {
                    return redirect()->back()->with('error', 'Account is inactive.');
                }

                // Fetch Role Name
                $roleModel = new \App\Models\RoleModel();
                $role = $roleModel->find($user->role_id);
                $roleName = $role ? $role->name : 'guest';

                // Set Session
                $sessionData = [
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'fullname'  => $user->fullname,
                    'photo'     => $user->photo,
                    'role'      => $roleName,
                    'logged_in' => true,
                ];
                session()->set($sessionData);

                // Update Last Login
                $userModel->update($user->id, ['last_login' => date('Y-m-d H:i:s')]);

                // Log Activity
                log_activity('login');

                return redirect()->to('/dashboard');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        helper('auth'); // Ensure helper is loaded
        log_activity('logout');
        session()->destroy();
        return redirect()->to('/login');
    }
}
