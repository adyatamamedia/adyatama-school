<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;
use App\Models\SeoModel;

class Pages extends BaseController
{
    protected $pageModel;
    protected $seoModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
        $this->seoModel = new SeoModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->pageModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('content', $search)
                ->orLike('slug', $search)
                ->groupEnd();
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder->orderBy('created_at', 'ASC');
                break;
            case 'title_asc':
                $builder->orderBy('title', 'ASC');
                break;
            case 'title_desc':
                $builder->orderBy('title', 'DESC');
                break;
            case 'newest':
            default:
                $builder->orderBy('created_at', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Static Pages',
            'pages' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'title_asc' => 'Judul A-Z',
                'title_desc' => 'Judul Z-A'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus halaman terpilih?'],
                ['action' => 'draft', 'label' => 'Draft', 'icon' => 'file', 'variant' => 'warning', 'confirm' => 'Ubah ke draft?'],
                ['action' => 'publish', 'label' => 'Publish', 'icon' => 'check', 'variant' => 'success', 'confirm' => 'Publish halaman?']
            ],
            'createButton' => [
                'url' => base_url('dashboard/pages/new'),
                'label' => 'Buat Halaman'
            ]
        ];

        return view('admin/pages/index', $data);
    }

    public function new()
    {
        $data = ['title' => 'Create New Page'];
        return view('admin/pages/create', $data);
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[draft,published]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);
        
        if ($this->pageModel->where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        // Load HTML sanitization helper
        helper('html');

        $this->pageModel->save([
            'title' => $title,
            'slug' => $slug,
            'content' => sanitize_html($this->request->getPost('content')),
            'featured_image' => $this->request->getPost('featured_image'),
            'status' => $this->request->getPost('status'),
        ]);
        
        $pageId = $this->pageModel->insertID();

        // Handle SEO Data
        $seoData = $this->request->getPost('seo');
        if ($seoData && is_array($seoData)) {
            $this->seoModel->save([
                'subject_type' => 'page',
                'subject_id' => $pageId,
                'meta_title' => $seoData['meta_title'],
                'meta_description' => $seoData['meta_description'],
                'meta_keywords' => $seoData['meta_keywords'],
                'canonical' => $seoData['canonical'],
                'robots' => $seoData['robots'],
            ]);
        }

        return redirect()->to('/dashboard/pages')->with('message', 'Page created successfully.');
    }

    public function edit($id)
    {
        $page = $this->pageModel->find($id);
        if (! $page) {
            return redirect()->to('/dashboard/pages')->with('error', 'Page not found.');
        }

        $data = [
            'title' => 'Edit Page',
            'page' => $page,
            'seo' => $this->seoModel->getSeo('page', $id)
        ];

        return view('admin/pages/edit', $data);
    }

    public function update($id)
    {
        $page = $this->pageModel->find($id);
        if (! $page) {
            return redirect()->to('/dashboard/pages')->with('error', 'Page not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[draft,published]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Load HTML sanitization helper
        helper('html');

        $this->pageModel->update($id, [
            'title' => $this->request->getPost('title'),
            'content' => sanitize_html($this->request->getPost('content')),
            'featured_image' => $this->request->getPost('featured_image'),
            'status' => $this->request->getPost('status'),
        ]);

        // Handle SEO Data Update
        $seoData = $this->request->getPost('seo');
        if ($seoData && is_array($seoData)) {
            $existingSeo = $this->seoModel->getSeo('page', $id);
            $saveData = [
                'subject_type' => 'page',
                'subject_id' => $id,
                'meta_title' => $seoData['meta_title'],
                'meta_description' => $seoData['meta_description'],
                'meta_keywords' => $seoData['meta_keywords'],
                'canonical' => $seoData['canonical'],
                'robots' => $seoData['robots'],
            ];
            
            if ($existingSeo) {
                $saveData['id'] = $existingSeo->id;
            }
            
            $this->seoModel->save($saveData);
        }

        return redirect()->to('/dashboard/pages')->with('message', 'Page updated successfully.');
    }

    public function delete($id)
    {
        $this->pageModel->delete($id);
        return redirect()->to('/dashboard/pages')->with('message', 'Page deleted successfully.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->delete($id)) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) deleted successfully.");
    }

    public function bulkDraft()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->update($id, ['status' => 'draft'])) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) moved to draft.");
    }

    public function bulkPublish()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->update($id, ['status' => 'published', 'published_at' => date('Y-m-d H:i:s')])) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) published.");
    }
}
