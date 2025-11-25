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
        $data = [
            'title' => 'Manage Categories',
            'categories' => $this->categoryModel->findAll()
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
}
