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
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'name_asc';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->ekskulModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'name_desc':
                $builder->orderBy('name', 'DESC');
                break;
            case 'newest':
                $builder->orderBy('created_at', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('created_at', 'ASC');
                break;
            case 'name_asc':
            default:
                $builder->orderBy('name', 'ASC');
                break;
        }
        
        $data = [
            'title' => 'Extracurriculars',
            'ekskuls' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'name_asc' => 'Nama A-Z',
                'name_desc' => 'Nama Z-A',
                'newest' => 'Terbaru',
                'oldest' => 'Terlama'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus ekstrakurikuler terpilih?']
            ],
            'createButton' => [
                'url' => '#',
                'label' => 'Buat Ekstrakurikuler',
                'modal' => 'createEkskulModal'
            ]
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

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No extracurriculars selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->ekskulModel->delete($id)) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/extracurriculars')->with('message', "$count extracurricular(s) deleted successfully.");
    }
}
