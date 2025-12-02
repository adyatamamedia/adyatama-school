<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruStaffModel;

class GuruStaff extends BaseController
{
    protected $guruStaffModel;

    public function __construct()
    {
        $this->guruStaffModel = new GuruStaffModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'name_asc';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->guruStaffModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('posisi', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'name_desc':
                $builder->orderBy('nama_lengkap', 'DESC');
                break;
            case 'position':
                $builder->orderBy('posisi', 'ASC');
                break;
            case 'newest':
                $builder->orderBy('created_at', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('created_at', 'ASC');
                break;
            case 'name_asc':
            default:
                $builder->orderBy('nama_lengkap', 'ASC');
                break;
        }
        
        $data = [
            'title' => 'Guru & Staff',
            'staff_list' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'name_asc' => 'Nama A-Z',
                'name_desc' => 'Nama Z-A',
                'position' => 'Posisi',
                'newest' => 'Terbaru',
                'oldest' => 'Terlama'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus guru/staff terpilih?']
            ],
            'createButton' => [
                'url' => base_url('dashboard/guru-staff/new'),
                'label' => 'Tambah Guru/Staff'
            ]
        ];

        return view('admin/guru_staff/index', $data);
    }

    public function new()
    {
        $data = ['title' => 'Add Guru/Staff'];
        return view('admin/guru_staff/create', $data);
    }

    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jabatan' => 'required|max_length[100]',
            'bidang' => 'required|max_length[100]',
            'status' => 'required|in_list[guru,staff,kepala-sekolah,tenaga-administrasi,tenaga-perpustakaan,tenaga-laboratorium,tenaga-kebersihan,tenaga-keamanan,bendahara,operator]',
            'email' => 'permit_empty|valid_email',
            'foto' => 'permit_empty|max_length[255]',
            'bio' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->guruStaffModel->save([
            'nip' => $this->request->getPost('nip'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jabatan' => $this->request->getPost('jabatan'),
            'bidang' => $this->request->getPost('bidang'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'bio' => $this->request->getPost('bio'),
            'foto' => $this->request->getPost('foto'), // Path from Media Library
            'status' => $this->request->getPost('status'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);
        
        $staffId = $this->guruStaffModel->getInsertID();
        helper('auth');
        log_activity('create_guru_staff', 'guru_staff', $staffId, ['nama' => $this->request->getPost('nama_lengkap')]);

        return redirect()->to('/dashboard/guru-staff')->with('message', 'Data saved successfully.');
    }

    public function edit($id)
    {
        $staff = $this->guruStaffModel->find($id);
        if (! $staff) {
            return redirect()->to('/dashboard/guru-staff')->with('error', 'Data not found.');
        }

        $data = [
            'title' => 'Edit Guru/Staff',
            'staff' => $staff
        ];

        return view('admin/guru_staff/edit', $data);
    }

    public function update($id)
    {
        $staff = $this->guruStaffModel->find($id);
        if (! $staff) {
            return redirect()->to('/dashboard/guru-staff')->with('error', 'Data not found.');
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jabatan' => 'required|max_length[100]',
            'bidang' => 'required|max_length[100]',
            'status' => 'required|in_list[guru,staff,kepala-sekolah,tenaga-administrasi,tenaga-perpustakaan,tenaga-laboratorium,tenaga-kebersihan,tenaga-keamanan,bendahara,operator]',
            'email' => 'permit_empty|valid_email',
            'foto' => 'permit_empty|max_length[255]',
            'bio' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->guruStaffModel->update($id, [
            'nip' => $this->request->getPost('nip'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jabatan' => $this->request->getPost('jabatan'),
            'bidang' => $this->request->getPost('bidang'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'bio' => $this->request->getPost('bio'),
            'foto' => $this->request->getPost('foto'),
            'status' => $this->request->getPost('status'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);
        
        helper('auth');
        log_activity('update_guru_staff', 'guru_staff', $id, ['nama' => $this->request->getPost('nama_lengkap')]);

        return redirect()->to('/dashboard/guru-staff')->with('message', 'Data updated successfully.');
    }

    public function delete($id)
    {
        helper('auth');
        
        $staff = $this->guruStaffModel->find($id);
        $this->guruStaffModel->delete($id);
        
        log_activity('delete_guru_staff', 'guru_staff', $id, ['nama' => $staff->nama_lengkap ?? null]);
        
        return redirect()->to('/dashboard/guru-staff')->with('message', 'Data deleted successfully.');
    }

    public function bulkDelete()
    {
        helper('auth');

        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No guru/staff selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->guruStaffModel->delete($id)) {
                log_activity('bulk_delete_guru_staff', 'guru_staff', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/guru-staff')->with('message', $count . ' guru/staff deleted successfully.');
    }
}

