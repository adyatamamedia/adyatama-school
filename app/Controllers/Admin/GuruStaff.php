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
        $data = [
            'title' => 'Guru & Staff',
            'staff_list' => $this->guruStaffModel->orderBy('nama_lengkap', 'ASC')->findAll()
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
            'status' => 'required|in_list[guru,staff]',
            'email' => 'permit_empty|valid_email',
            'foto' => 'permit_empty|max_length[255]',
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
            'foto' => $this->request->getPost('foto'), // Path from Media Library
            'status' => $this->request->getPost('status'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

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
            'status' => 'required|in_list[guru,staff]',
            'email' => 'permit_empty|valid_email',
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
            'foto' => $this->request->getPost('foto'),
            'status' => $this->request->getPost('status'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/dashboard/guru-staff')->with('message', 'Data updated successfully.');
    }

    public function delete($id)
    {
        $this->guruStaffModel->delete($id);
        return redirect()->to('/dashboard/guru-staff')->with('message', 'Data deleted successfully.');
    }
}
