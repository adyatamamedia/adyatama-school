<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExtracurricularModel;

class Extracurriculars extends BaseController
{
    protected $ekskulModel;

    public function __construct()
    {
        $this->ekskulModel = new ExtracurricularModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Extracurriculars',
            'ekskuls' => $this->ekskulModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('admin/extracurriculars/index', $data);
    }

    public function new()
    {
        $data = ['title' => 'Create Extracurricular'];
        return view('admin/extracurriculars/create', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[120]|is_unique[extracurriculars.name]',
            'description' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->ekskulModel->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/dashboard/extracurriculars')->with('message', 'Extracurricular created successfully.');
    }

    public function edit($id)
    {
        $ekskul = $this->ekskulModel->find($id);
        if (! $ekskul) {
            return redirect()->to('/dashboard/extracurriculars')->with('error', 'Data not found.');
        }

        $data = [
            'title' => 'Edit Extracurricular',
            'ekskul' => $ekskul
        ];

        return view('admin/extracurriculars/edit', $data);
    }

    public function update($id)
    {
        $ekskul = $this->ekskulModel->find($id);
        if (! $ekskul) {
            return redirect()->to('/dashboard/extracurriculars')->with('error', 'Data not found.');
        }

        $rules = [
            'name' => "required|min_length[3]|max_length[120]|is_unique[extracurriculars.name,id,{$id}]",
            'description' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->ekskulModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/dashboard/extracurriculars')->with('message', 'Extracurricular updated successfully.');
    }

    public function delete($id)
    {
        $this->ekskulModel->delete($id);
        return redirect()->to('/dashboard/extracurriculars')->with('message', 'Extracurricular deleted successfully.');
    }
}
