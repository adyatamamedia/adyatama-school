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
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->userModel
            ->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id', 'left');
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('users.fullname', $search)
                ->orLike('users.email', $search)
                ->orLike('users.username', $search)
                ->orLike('roles.name', $search)
                ->groupEnd();
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder->orderBy('users.created_at', 'ASC');
                break;
            case 'name_asc':
                $builder->orderBy('users.fullname', 'ASC');
                break;
            case 'name_desc':
                $builder->orderBy('users.fullname', 'DESC');
                break;
            case 'role':
                $builder->orderBy('roles.name', 'ASC');
                break;
            case 'newest':
            default:
                $builder->orderBy('users.created_at', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Manage Users',
            'users' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'name_asc' => 'Nama A-Z',
                'name_desc' => 'Nama Z-A',
                'role' => 'Role'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus user terpilih?']
            ],
            'createButton' => [
                'url' => base_url('dashboard/users/new'),
                'label' => 'Buat User'
            ]
        ];

        return view('admin/users/index', $data);
    }

    public function new()
    {
        helper('auth');
        $user = current_user();
        
        // Get all roles
        $roles = $this->roleModel->findAll();
        
        // If operator, exclude admin role
        if ($user->role === 'operator') {
            $roles = array_filter($roles, function($role) {
                return $role->name !== 'admin';
            });
        }
        
        $data = [
            'title' => 'Create New User',
            'roles' => $roles
        ];
        return view('admin/users/create', $data);
    }

    public function create()
    {
        helper('auth');
        $user = current_user();
        
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
        
        // Check if operator trying to create admin
        $roleId = $this->request->getPost('role_id');
        $role = $this->roleModel->find($roleId);
        
        if ($user->role === 'operator' && $role && $role->name === 'admin') {
            return redirect()->back()->withInput()->with('error', 'You do not have permission to create admin users.');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'  => $this->request->getPost('role_id'),
            'status'   => $this->request->getPost('status'),
        ];

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Validate photo
            $photoRules = [
                'photo' => [
                    'rules' => 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]',
                    'errors' => [
                        'max_size' => 'Image size cannot exceed 2MB',
                        'is_image' => 'Please select a valid image file',
                        'mime_in' => 'Only JPG, PNG, and GIF images are allowed'
                    ]
                ]
            ];

            if ($this->validate($photoRules)) {
                // Create uploads/users directory if not exists
                $uploadPath = FCPATH . 'uploads/users';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate random filename
                $newName = $photo->getRandomName();
                
                // Move file
                $photo->move($uploadPath, $newName);
                
                // Store relative path in database
                $data['photo'] = 'uploads/users/' . $newName;
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $this->userModel->save($data);
        
        $userId = $this->userModel->getInsertID();
        log_activity('create_user', 'user', $userId, ['username' => $data['username'], 'role' => $role->name ?? null]);

        return redirect()->to('/dashboard/users')->with('message', 'User created successfully.');
    }

    public function edit($id)
    {
        helper('auth');
        $currentUser = current_user();
        
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/dashboard/users')->with('error', 'User not found.');
        }
        
        // Get all roles
        $roles = $this->roleModel->findAll();
        
        // If operator, exclude admin role
        if ($currentUser->role === 'operator') {
            $roles = array_filter($roles, function($role) {
                return $role->name !== 'admin';
            });
            
            // Prevent operator from editing admin users
            $userRole = $this->roleModel->find($user->role_id);
            if ($userRole && $userRole->name === 'admin') {
                return redirect()->to('/dashboard/users')->with('error', 'You do not have permission to edit admin users.');
            }
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        helper('auth');
        $currentUser = current_user();
        
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/dashboard/users')->with('error', 'User not found.');
        }
        
        // Check if operator trying to edit admin user
        $userRole = $this->roleModel->find($user->role_id);
        if ($currentUser->role === 'operator' && $userRole && $userRole->name === 'admin') {
            return redirect()->to('/dashboard/users')->with('error', 'You do not have permission to edit admin users.');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'role_id'  => 'required|numeric',
            'fullname' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if operator trying to assign admin role
        $newRoleId = $this->request->getPost('role_id');
        $newRole = $this->roleModel->find($newRoleId);
        
        if ($currentUser->role === 'operator' && $newRole && $newRole->name === 'admin') {
            return redirect()->back()->withInput()->with('error', 'You do not have permission to assign admin role.');
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

        // Handle delete photo checkbox
        if ($this->request->getPost('delete_photo') == '1') {
            // Delete physical file if exists
            if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
                @unlink(FCPATH . $user->photo);
            }
            // Set photo to null in database
            $data['photo'] = null;
        }

        // Handle new photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Validate photo
            $photoRules = [
                'photo' => [
                    'rules' => 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]',
                    'errors' => [
                        'max_size' => 'Image size cannot exceed 2MB',
                        'is_image' => 'Please select a valid image file',
                        'mime_in' => 'Only JPG, PNG, and GIF images are allowed'
                    ]
                ]
            ];

            if ($this->validate($photoRules)) {
                // Delete old photo if exists (only if not already deleted by checkbox)
                if (!$this->request->getPost('delete_photo') && !empty($user->photo) && file_exists(FCPATH . $user->photo)) {
                    @unlink(FCPATH . $user->photo);
                }

                // Create uploads/users directory if not exists
                $uploadPath = FCPATH . 'uploads/users';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate random filename
                $newName = $photo->getRandomName();
                
                // Move file
                $photo->move($uploadPath, $newName);
                
                // Store relative path in database
                $data['photo'] = 'uploads/users/' . $newName;
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $this->userModel->update($id, $data);
        
        log_activity('update_user', 'user', $id, ['username' => $data['username'], 'role' => $newRole->name ?? null]);

        return redirect()->to('/dashboard/users')->with('message', 'User updated successfully.');
    }

    public function delete($id)
    {
        helper('auth');
        $currentUser = current_user();
        
        if ($currentUser->id == $id) {
            return redirect()->to('/dashboard/users')->with('error', 'You cannot delete yourself.');
        }
        
        // Check if operator trying to delete admin user
        $user = $this->userModel->find($id);
        if ($user) {
            $userRole = $this->roleModel->find($user->role_id);
            if ($currentUser->role === 'operator' && $userRole && $userRole->name === 'admin') {
                return redirect()->to('/dashboard/users')->with('error', 'You do not have permission to delete admin users.');
            }
        }
        
        $this->userModel->delete($id);
        
        log_activity('delete_user', 'user', $id, ['username' => $user->username ?? null]);
        
        return redirect()->to('/dashboard/users')->with('message', 'User deleted successfully.');
    }

    public function deletePhoto($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
            @unlink(FCPATH . $user->photo);
            
            // Update database
            $this->userModel->update($id, ['photo' => null]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Photo deleted successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'No photo to delete']);
    }

    public function bulkDelete()
    {
        helper('auth');
        $currentUser = current_user();
        
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        $count = 0;
        $skipped = 0;

        foreach ($ids as $id) {
            if ($id == $currentUser->id) {
                $skipped++;
                continue; // Skip current user
            }
            
            // Check if operator trying to delete admin
            if ($currentUser->role === 'operator') {
                $user = $this->userModel->find($id);
                if ($user) {
                    $userRole = $this->roleModel->find($user->role_id);
                    if ($userRole && $userRole->name === 'admin') {
                        $skipped++;
                        continue; // Skip admin users for operator
                    }
                }
            }
            
            if ($this->userModel->delete($id)) {
                log_activity('bulk_delete_user', 'user', $id);
                $count++;
            }
        }

        $message = "$count user(s) deleted successfully.";
        if ($skipped > 0) {
            $message .= " ($skipped skipped)";
        }

        return redirect()->to('/dashboard/users')->with('message', $message);
    }
}
