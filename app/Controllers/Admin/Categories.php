<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'name_asc';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->categoryModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('slug', $search)
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
            'title' => 'Manage Categories',
            'categories' => $builder->paginate($perPage, 'default'),
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
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus kategori terpilih?']
            ],
            'createButton' => [
                'url' => '#',
                'label' => 'Buat Kategori',
                'modal' => 'createCategoryModal'
            ]
        ];

        return view('admin/categories/index', $data);
    }

    public function new()
    {
        $data = ['title' => 'Create New Category'];
        return view('admin/categories/create', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        // Ensure unique slug manually if needed or rely on validation rule in model (but slug is auto-generated here)
        // Simple uniqueness check for slug collision
        if ($this->categoryModel->where('slug', $slug)->first()) {
             $slug = $slug . '-' . time();
        }

        $this->categoryModel->save([
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/dashboard/categories')->with('message', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/dashboard/categories')->with('error', 'Category not found.');
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category
        ];

        return view('admin/categories/edit', $data);
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/dashboard/categories')->with('error', 'Category not found.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
             'description' => 'permit_empty|max_length[500]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        // Update slug only if name changes? usually better to keep slug stable for SEO unless forced.
        // For this simple implementation, let's keep slug stable unless user explicitly wanted (not implemented in UI).
        // Just updating name and desc.
        
        $this->categoryModel->update($id, [
            'name' => $name,
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/dashboard/categories')->with('message', 'Category updated successfully.');
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/dashboard/categories')->with('message', 'Category deleted successfully.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No categories selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->categoryModel->delete($id)) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/categories')->with('message', "$count categor(ies) deleted successfully.");
    }
}
