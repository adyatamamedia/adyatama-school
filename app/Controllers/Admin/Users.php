<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class Users extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Users',
            'users' => $this->userModel
                ->select('users.*, roles.name as role_name')
                ->join('roles', 'roles.id = users.role_id', 'left')
                ->findAll()
        ];

        return view('admin/users/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New User',
            'roles' => $this->roleModel->findAll()
        ];
        return view('admin/users/create', $data);
    }

    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'    => 'permit_empty|valid_email',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required|numeric',
            'fullname' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'  => $this->request->getPost('role_id'),
            'status'   => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/users')->with('message', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/dashboard/users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $this->roleModel->findAll()
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/dashboard/users')->with('error', 'User not found.');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'role_id'  => 'required|numeric',
            'fullname' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'role_id'  => $this->request->getPost('role_id'),
            'status'   => $this->request->getPost('status'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/dashboard/users')->with('message', 'User updated successfully.');
    }

    public function delete($id)
    {
        if (current_user()->id == $id) {
            return redirect()->to('/dashboard/users')->with('error', 'You cannot delete yourself.');
        }
        
        $this->userModel->delete($id);
        return redirect()->to('/dashboard/users')->with('message', 'User deleted successfully.');
    }
}
