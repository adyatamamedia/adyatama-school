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
            'enableTrash' => true,
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
            'slug' => 'required|is_unique[pages.slug]|max_length[255]',
            'status' => 'required|in_list[draft,published]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        // Fallback: if slug is empty, generate from title
        if (empty($slug)) {
            $slug = url_title($title, '-', true);
            if ($this->pageModel->where('slug', $slug)->first()) {
                $slug = $slug . '-' . time();
            }
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
        
        helper('auth');
        log_activity('create_page', 'page', $pageId, ['title' => $title]);

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
            'slug' => "required|is_unique[pages.slug,id,{$id}]|max_length[255]",
            'status' => 'required|in_list[draft,published]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Load HTML sanitization helper
        helper('html');

        $slug = $this->request->getPost('slug');

        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'content' => sanitize_html($this->request->getPost('content')),
            'featured_image' => $this->request->getPost('featured_image'),
            'status' => $this->request->getPost('status'),
        ];

        if (!$this->pageModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->pageModel->errors());
        }

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
        
        helper('auth');
        log_activity('update_page', 'page', $id, ['title' => $this->request->getPost('title')]);

        return redirect()->to('/dashboard/pages')->with('message', 'Page updated successfully.');
    }

    public function delete($id)
    {
        helper('auth');
        
        $page = $this->pageModel->find($id);
        $this->pageModel->delete($id);
        
        log_activity('delete_page', 'page', $id, ['title' => $page->title ?? null]);
        
        return redirect()->to('/dashboard/pages')->with('message', 'Page deleted successfully.');
    }

    public function bulkDelete()
    {
        helper('auth');
        
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->delete($id)) {
                log_activity('bulk_delete_page', 'page', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) deleted successfully.");
    }

    public function bulkDraft()
    {
        helper('auth');
        
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->update($id, ['status' => 'draft'])) {
                log_activity('bulk_draft_page', 'page', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) moved to draft.");
    }

    public function bulkPublish()
    {
        helper('auth');
        
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->pageModel->update($id, ['status' => 'published', 'published_at' => date('Y-m-d H:i:s')])) {
                log_activity('bulk_publish_page', 'page', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/pages')->with('message', "$count page(s) published.");
    }

    public function trash()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $search = $this->request->getGet('search') ?? '';
        
        // Build query for deleted items
        $builder = $this->pageModel->onlyDeleted();
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('content', $search)
                ->orLike('slug', $search)
                ->groupEnd();
        }
        
        $builder->orderBy('deleted_at', 'DESC');
        
        $data = [
            'title' => 'Trash - Static Pages',
            'pages' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'search' => $search,
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'restore', 'label' => 'Restore', 'icon' => 'trash-restore', 'variant' => 'success', 'confirm' => 'Restore halaman terpilih?'],
                ['action' => 'force-delete', 'label' => 'Delete Permanently', 'icon' => 'ban', 'variant' => 'danger', 'confirm' => 'Hapus permanen? Tidak bisa dikembalikan!']
            ]
        ];

        return view('admin/pages/trash', $data);
    }

    public function restore($id)
    {
        // Restore specific page
        $page = $this->pageModel->onlyDeleted()->find($id);
        if (!$page) {
            return redirect()->to('/dashboard/pages/trash')->with('error', 'Page not found in trash.');
        }

        helper('auth');
        $db = \Config\Database::connect();
        $db->table('pages')->where('id', $id)->update(['deleted_at' => null]);
        
        log_activity('restore_page', 'page', $id, ['title' => $page->title ?? null]);
        return redirect()->to('/dashboard/pages/trash')->with('message', 'Page restored successfully.');
    }

    public function bulkRestore()
    {
        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $db = \Config\Database::connect();
        $count = 0;
        foreach ($ids as $id) {
            $db->table('pages')->where('id', $id)->update(['deleted_at' => null]);
            log_activity('bulk_restore_page', 'page', $id);
            $count++;
        }

        return redirect()->to('/dashboard/pages/trash')->with('message', "$count page(s) restored.");
    }

    public function bulkForceDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No pages selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $this->pageModel->delete($id, true); // True for purge/force delete
            log_activity('bulk_force_delete_page', 'page', $id);
            $count++;
        }

        return redirect()->to('/dashboard/pages/trash')->with('message', "$count page(s) permanently deleted.");
    }
}
