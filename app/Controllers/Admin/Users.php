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

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        $currentUserId = current_user()->id;
        $count = 0;
        $skipped = 0;

        foreach ($ids as $id) {
            if ($id == $currentUserId) {
                $skipped++;
                continue; // Skip current user
            }
            if ($this->userModel->delete($id)) {
                $count++;
            }
        }

        $message = "$count user(s) deleted successfully.";
        if ($skipped > 0) {
            $message .= " ($skipped skipped - cannot delete yourself)";
        }

        return redirect()->to('/dashboard/users')->with('message', $message);
    }
}
