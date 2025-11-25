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
        $data = [
            'title' => 'Static Pages',
            'pages' => $this->pageModel->orderBy('title', 'ASC')->findAll()
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

        $this->pageModel->save([
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
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

        $this->pageModel->update($id, [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
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
}
