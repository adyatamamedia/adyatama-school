<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\SeoModel;

class Posts extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $seoModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->seoModel = new SeoModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Posts',
            'posts' => $this->postModel
                ->select('posts.*, categories.name as category_name, users.fullname as author_name')
                ->join('categories', 'categories.id = posts.category_id', 'left')
                ->join('users', 'users.id = posts.author_id', 'left')
                ->orderBy('posts.created_at', 'DESC')
                ->findAll()
        ];

        return view('admin/posts/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New Post',
            'categories' => $this->categoryModel->findAll(),
            'users' => (current_user()->role == 'admin') ? (new \App\Models\UserModel())->findAll() : []
        ];
        return view('admin/posts/create', $data);
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'permit_empty',
            'category_id' => 'permit_empty|numeric',
            'status' => 'required|in_list[draft,published]',
            'author_id' => 'permit_empty|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);
        
        // Simple slug uniqueness
        if ($this->postModel->where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        // Determine Author
        $authorId = session('user_id');
        if (current_user()->role == 'admin' && $this->request->getPost('author_id')) {
            $authorId = $this->request->getPost('author_id');
        }

        $data = [
            'author_id' => $authorId,
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'featured_media_id' => $this->request->getPost('featured_media_id') ?: null,
            'comments_enabled' => $this->request->getPost('comments_enabled') ? 1 : 0,
            'react_enabled' => $this->request->getPost('react_enabled') ? 1 : 0,
            'status' => $this->request->getPost('status'),
            'published_at' => ($this->request->getPost('status') == 'published') ? date('Y-m-d H:i:s') : null,
        ];

        // Insert the post first
        $postId = $this->postModel->insert($data);

        if (!$postId) {
             return redirect()->back()->withInput()->with('errors', $this->postModel->errors());
        }

        // Handle SEO Data
        $seoData = $this->request->getPost('seo');
        if ($seoData && is_array($seoData)) {
            $this->seoModel->save([
                'subject_type' => 'post',
                'subject_id' => $postId,
                'meta_title' => $seoData['meta_title'],
                'meta_description' => $seoData['meta_description'],
                'meta_keywords' => $seoData['meta_keywords'],
                'canonical' => $seoData['canonical'],
                'robots' => $seoData['robots'],
            ]);
        }

        log_activity('create_post', 'post', $postId);

        return redirect()->to('/dashboard/posts')->with('message', 'Post created successfully.');
    }

    public function edit($id)
    {
        $post = $this->postModel->find($id);
        if (! $post) {
            return redirect()->to('/dashboard/posts')->with('error', 'Post not found.');
        }

        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $this->categoryModel->findAll(),
            'users' => (current_user()->role == 'admin') ? (new \App\Models\UserModel())->findAll() : [],
            'seo' => $this->seoModel->getSeo('post', $id)
        ];

        return view('admin/posts/edit', $data);
    }

    public function update($id)
    {
        $post = $this->postModel->find($id);
        if (! $post) {
            return redirect()->to('/dashboard/posts')->with('error', 'Post not found.');
        }

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'status' => 'required|in_list[draft,published]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'featured_media_id' => $this->request->getPost('featured_media_id') ?: null,
            'comments_enabled' => $this->request->getPost('comments_enabled') ? 1 : 0,
            'react_enabled' => $this->request->getPost('react_enabled') ? 1 : 0,
            'status' => $this->request->getPost('status'),
        ];

        // Admin can change author
        if (current_user()->role == 'admin' && $this->request->getPost('author_id')) {
            $data['author_id'] = $this->request->getPost('author_id');
        }

        // Update published_at only if changing from draft to published
        if ($post->status == 'draft' && $data['status'] == 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $this->postModel->update($id, $data);

        // Handle SEO Data Update
        $seoData = $this->request->getPost('seo');
        if ($seoData && is_array($seoData)) {
            $existingSeo = $this->seoModel->getSeo('post', $id);
            $saveData = [
                'subject_type' => 'post',
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

        log_activity('update_post', 'post', $id);

        return redirect()->to('/dashboard/posts')->with('message', 'Post updated successfully.');
    }

    public function delete($id)
    {
        $this->postModel->delete($id);
        log_activity('delete_post', 'post', $id);
        return redirect()->to('/dashboard/posts')->with('message', 'Post deleted successfully.');
    }
}
