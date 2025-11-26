<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\SeoModel;
use App\Models\TagModel;

class Posts extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $seoModel;
    protected $tagModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->seoModel = new SeoModel();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';
        $filterCategory = $this->request->getGet('category') ?? '';
        $filterAuthor = $this->request->getGet('author') ?? '';
        
        // Build query
        $builder = $this->postModel
            ->select('posts.*, categories.name as category_name, users.fullname as author_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left');
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('posts.title', $search)
                ->orLike('posts.content', $search)
                ->orLike('posts.excerpt', $search)
                ->groupEnd();
        }
        
        // Apply category filter
        if ($filterCategory) {
            $builder->where('posts.category_id', $filterCategory);
        }
        
        // Apply author filter
        if ($filterAuthor) {
            $builder->where('posts.author_id', $filterAuthor);
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder->orderBy('posts.created_at', 'ASC');
                break;
            case 'title_asc':
                $builder->orderBy('posts.title', 'ASC');
                break;
            case 'title_desc':
                $builder->orderBy('posts.title', 'DESC');
                break;
            case 'category':
                $builder->orderBy('categories.name', 'ASC');
                break;
            case 'author':
                $builder->orderBy('users.fullname', 'ASC');
                break;
            case 'newest':
            default:
                $builder->orderBy('posts.created_at', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Manage Posts',
            'posts' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'filterCategory' => $filterCategory,
            'filterAuthor' => $filterAuthor,
            'categories' => $this->categoryModel->findAll(),
            'users' => model('App\Models\UserModel')->findAll(),
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'title_asc' => 'Judul A-Z',
                'title_desc' => 'Judul Z-A',
                'category' => 'Kategori',
                'author' => 'Penulis'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus post terpilih?'],
                ['action' => 'draft', 'label' => 'Draft', 'icon' => 'file', 'variant' => 'warning', 'confirm' => 'Ubah ke draft?'],
                ['action' => 'publish', 'label' => 'Publish', 'icon' => 'check', 'variant' => 'success', 'confirm' => 'Publish post?']
            ],
            'createButton' => [
                'url' => base_url('dashboard/posts/new'),
                'label' => 'Buat Post'
            ]
        ];

        return view('admin/posts/index', $data);
    }

    public function new()
    {
        // Get all unique tags from database
        $tagModel = new \App\Models\TagModel();
        $existingTags = $tagModel->select('name')
            ->distinct()
            ->orderBy('name', 'ASC')
            ->findAll();
        
        $data = [
            'title' => 'Create New Post',
            'categories' => $this->categoryModel->findAll(),
            'users' => (current_user()->role == 'admin') ? (new \App\Models\UserModel())->findAll() : [],
            'existingTags' => array_column($existingTags, 'name')
        ];
        return view('admin/posts/create', $data);
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'slug' => 'required|min_length[3]|max_length[255]',
            'content' => 'permit_empty',
            'category_id' => 'permit_empty|numeric',
            'status' => 'required|in_list[draft,published]',
            'author_id' => 'permit_empty|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        // Make slug URL-friendly
        $slug = url_title($slug, '-', true);
        
        // Check slug uniqueness and auto-increment if exists
        $slug = $this->getUniqueSlug($slug);

        // Determine Author
        $authorId = session('user_id');
        if (current_user()->role == 'admin' && $this->request->getPost('author_id')) {
            $authorId = $this->request->getPost('author_id');
        }

        // Load helpers
        helper(['html', 'youtube']);

        $postType = $this->request->getPost('post_type') ?: 'article';
        $videoUrl = $this->request->getPost('video_url') ?: null;
        $featuredMediaId = $this->request->getPost('featured_media_id') ?: null;
        
        // Auto-fetch YouTube thumbnail if post is video type and no featured image set
        if ($postType === 'video' && !empty($videoUrl) && empty($featuredMediaId)) {
            log_message('info', 'Attempting to fetch YouTube thumbnail for new post: ' . $videoUrl);
            
            $thumbnailResult = download_youtube_thumbnail($videoUrl, $title);
            
            if ($thumbnailResult && isset($thumbnailResult['media_id'])) {
                $featuredMediaId = $thumbnailResult['media_id'];
                log_message('info', 'YouTube thumbnail fetched successfully. Media ID: ' . $featuredMediaId);
            } else {
                log_message('warning', 'Failed to fetch YouTube thumbnail for: ' . $videoUrl);
            }
        }

        $data = [
        'author_id' => $authorId,
        'post_type' => $postType,
        'title' => $title,
        'slug' => $slug,
        'content' => sanitize_html($this->request->getPost('content')),
        'excerpt' => $this->request->getPost('excerpt'),
        'category_id' => $this->request->getPost('category_id') ?: null,
        'featured_media_id' => $featuredMediaId,
        'video_url' => $videoUrl,
        'comments_enabled' => $this->request->getPost('comments_enabled') ? 1 : 0,
        'react_enabled' => $this->request->getPost('react_enabled') ? 1 : 0,
        'status' => $this->request->getPost('status'),
        'published_at' => null,
    ];

    // Handle published_at
    if ($data['status'] == 'published') {
        $publishedAt = $this->request->getPost('published_at');
        $data['published_at'] = $publishedAt ? date('Y-m-d H:i:s', strtotime($publishedAt)) : date('Y-m-d H:i:s');
    }
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

    // Handle Tags
    $tagsInput = $this->request->getPost('tags');
    if ($tagsInput) {
        $tagNames = array_map('trim', explode(',', $tagsInput));
        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $this->tagModel->insert([
                    'post_id' => $postId,
                    'name' => $tagName,
                    'slug' => url_title($tagName, '-', true)
                ]);
            }
        }
    }

        log_activity('create_post', 'post', $postId);

        // Add notification about YouTube thumbnail if it was auto-fetched
        $message = 'Post created successfully.';
        if ($postType === 'video' && !empty($videoUrl) && !$this->request->getPost('featured_media_id') && !empty($featuredMediaId)) {
            $message .= ' YouTube thumbnail automatically fetched and set as featured image.';
        }

        return redirect()->to('/dashboard/posts')->with('message', $message);
    }

    public function edit($id)
    {
        $post = $this->postModel->find($id);
        if (! $post) {
            return redirect()->to('/dashboard/posts')->with('error', 'Post not found.');
        }

        // Load featured media if exists
        if ($post->featured_media_id) {
            $mediaModel = new \App\Models\MediaModel();
            $post->featured_media = $mediaModel->find($post->featured_media_id);
        }

        // Get all unique tags from database for suggestions
        $existingTags = $this->tagModel->select('name')
            ->distinct()
            ->orderBy('name', 'ASC')
            ->findAll();

        $data = [
        'title' => 'Edit Post',
        'post' => $post,
        'categories' => $this->categoryModel->findAll(),
        'users' => (current_user()->role == 'admin') ? (new \App\Models\UserModel())->findAll() : [],
        'seo' => $this->seoModel->getSeo('post', $id),
        'tags' => $this->tagModel->where('post_id', $id)->findAll(),
        'existingTags' => array_column($existingTags, 'name')
    ];
        return view('admin/posts/edit', $data);
    }

    public function update($id)
    {
        $post = $this->postModel->find($id);
        if (! $post) {
            return redirect()->to('/dashboard/posts')->with('error', 'Post not found.');
        }
        
        // Debug: Log all POST data
        log_message('debug', 'RAW POST Data: ' . json_encode($this->request->getPost()));

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'slug' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[draft,published]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug
        $slug = $this->request->getPost('slug');
        $slug = url_title($slug, '-', true);
        $slug = $this->getUniqueSlug($slug, $id); // Exclude current post ID

        // Load helpers
        helper(['html', 'youtube']);

        // Get post type and video URL
        $postType = $this->request->getPost('post_type') ?: 'article';
        $videoUrl = $this->request->getPost('video_url');
        
        // Debug log
        log_message('debug', 'Update Post - Type: ' . $postType . ', Video URL: ' . ($videoUrl ?? 'null'));

        // Get raw content
        $rawContent = $this->request->getPost('content');
        log_message('debug', 'Raw Content Length: ' . (strlen($rawContent ?? '')));
        
        // Temporary: Skip sanitization to test
        // $sanitizedContent = sanitize_html($rawContent);
        $sanitizedContent = $rawContent; // Use raw content temporarily
        log_message('debug', 'Sanitized Content Length: ' . (strlen($sanitizedContent ?? '')));

        $featuredMediaId = $this->request->getPost('featured_media_id') ?: null;
        
        // Auto-fetch YouTube thumbnail if post is video type and no featured image set
        if ($postType === 'video' && !empty($videoUrl) && empty($featuredMediaId)) {
            log_message('info', 'Attempting to fetch YouTube thumbnail for: ' . $videoUrl);
            
            $thumbnailResult = download_youtube_thumbnail($videoUrl, $this->request->getPost('title'));
            
            if ($thumbnailResult && isset($thumbnailResult['media_id'])) {
                $featuredMediaId = $thumbnailResult['media_id'];
                log_message('info', 'YouTube thumbnail fetched successfully. Media ID: ' . $featuredMediaId);
            } else {
                log_message('warning', 'Failed to fetch YouTube thumbnail for: ' . $videoUrl);
            }
        }

        $data = [
        'title' => $this->request->getPost('title'),
        'slug' => $slug,
        'post_type' => $postType,
        'content' => $sanitizedContent,
        'excerpt' => $this->request->getPost('excerpt'),
        'category_id' => $this->request->getPost('category_id') ?: null,
        'featured_media_id' => $featuredMediaId,
        'video_url' => $videoUrl ?: null,
        'comments_enabled' => $this->request->getPost('comments_enabled') ? 1 : 0,
        'react_enabled' => $this->request->getPost('react_enabled') ? 1 : 0,
        'status' => $this->request->getPost('status'),
    ];

        // Admin can change author
        if (current_user()->role == 'admin' && $this->request->getPost('author_id')) {
            $data['author_id'] = $this->request->getPost('author_id');
        }

        // Update published_at
    if ($data['status'] == 'published') {
        $publishedAt = $this->request->getPost('published_at');
        if ($publishedAt) {
            $data['published_at'] = date('Y-m-d H:i:s', strtotime($publishedAt));
        } elseif ($post->status == 'draft') {
            // Only set current time if changing from draft to published and no date provided
            $data['published_at'] = date('Y-m-d H:i:s');
        }
    } else {
        $data['published_at'] = null;
    }

        // Debug: Log data before update
        log_message('debug', 'Update Post Data: ' . json_encode($data));
        log_message('debug', 'video_url in data array: ' . (array_key_exists('video_url', $data) ? 'YES - ' . $data['video_url'] : 'NO'));
        
        // Try to update
        try {
            // Use query builder directly to bypass validation issues
            $db = \Config\Database::connect();
            $builder = $db->table('posts');
            
            // Add updated_at timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            // Debug: Final data being sent to DB
            log_message('debug', 'Final data with timestamp: ' . json_encode($data));
            
            // Perform update
            $updateResult = $builder->where('id', $id)->update($data);
            
            log_message('debug', 'Direct DB Update - Affected Rows: ' . $db->affectedRows());
            log_message('debug', 'Update query executed: ' . ($updateResult ? 'TRUE' : 'FALSE'));
            
            // Check for update errors
            if ($updateResult === false) {
                $error = $db->error();
                log_message('error', 'DB Update Failed - Query returned false');
                log_message('error', 'DB Error Code: ' . $error['code']);
                log_message('error', 'DB Error Message: ' . $error['message']);
                return redirect()->back()->withInput()->with('error', 'Update failed: ' . $error['message']);
            }
            
            // Note: affectedRows() can be 0 if no data changed, which is not an error
            $affectedRows = $db->affectedRows();
            log_message('debug', 'Update Result: SUCCESS - Affected Rows: ' . $affectedRows);
            
            // Force commit if autocommit is disabled
            if (!$db->transStatus()) {
                log_message('warning', 'Transaction status is false, attempting to commit...');
            }
            
            // Debug: Verify data after update using query builder
            $updatedPost = $builder->where('id', $id)->get()->getRow();
            if (!$updatedPost) {
                log_message('error', 'Post not found after update!');
                return redirect()->back()->withInput()->with('error', 'Post not found after update!');
            }
            
            log_message('debug', 'After Update - Post Type: ' . ($updatedPost->post_type ?? 'null') . ', Video URL: ' . ($updatedPost->video_url ?? 'null'));
            
        } catch (\Exception $e) {
            log_message('error', 'Update Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Update exception: ' . $e->getMessage());
        }

        // Handle SEO Data Update
        try {
            $seoData = $this->request->getPost('seo');
            if ($seoData && is_array($seoData)) {
                $existingSeo = $this->seoModel->getSeo('post', $id);
                $saveData = [
                    'subject_type' => 'post',
                    'subject_id' => $id,
                    'meta_title' => $seoData['meta_title'] ?? '',
                    'meta_description' => $seoData['meta_description'] ?? '',
                    'meta_keywords' => $seoData['meta_keywords'] ?? '',
                    'canonical' => $seoData['canonical'] ?? '',
                    'robots' => $seoData['robots'] ?? 'index,follow',
                ];
                
                if ($existingSeo) {
                    $saveData['id'] = $existingSeo->id;
                }
                
                $this->seoModel->save($saveData);
                log_message('debug', 'SEO data updated successfully');
            }
        } catch (\Exception $e) {
            log_message('error', 'SEO Update Error: ' . $e->getMessage());
            // Don't fail the whole update if SEO fails
        }

        // Handle Tags Update
        try {
            $tagsInput = $this->request->getPost('tags');
            // Delete existing tags
            $this->tagModel->where('post_id', $id)->delete();
            
            // Insert new tags
            if ($tagsInput) {
                $tagNames = array_map('trim', explode(',', $tagsInput));
                foreach ($tagNames as $tagName) {
                    if (!empty($tagName)) {
                        $this->tagModel->insert([
                            'post_id' => $id,
                            'name' => $tagName,
                            'slug' => url_title($tagName, '-', true)
                        ]);
                    }
                }
                log_message('debug', 'Tags updated successfully');
            }
        } catch (\Exception $e) {
            log_message('error', 'Tags Update Error: ' . $e->getMessage());
            // Don't fail the whole update if Tags fails
        }

        log_activity('update_post', 'post', $id);

        // Add notification about YouTube thumbnail if it was auto-fetched
        $message = 'Post updated successfully.';
        if ($postType === 'video' && !empty($videoUrl) && !$this->request->getPost('featured_media_id') && !empty($featuredMediaId)) {
            $message .= ' YouTube thumbnail automatically fetched and set as featured image.';
        }

        return redirect()->to('/dashboard/posts')->with('message', $message);
    }

    public function delete($id)
    {
        $this->postModel->delete($id);
        log_activity('delete_post', 'post', $id);
        return redirect()->to('/dashboard/posts')->with('message', 'Post deleted successfully.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No posts selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->postModel->delete($id)) {
                log_activity('bulk_delete_post', 'post', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/posts')->with('message', "$count post(s) deleted successfully.");
    }

    public function bulkDraft()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No posts selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->postModel->update($id, ['status' => 'draft'])) {
                log_activity('bulk_draft_post', 'post', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/posts')->with('message', "$count post(s) moved to draft.");
    }

    public function bulkPublish()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No posts selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->postModel->update($id, ['status' => 'published', 'published_at' => date('Y-m-d H:i:s')])) {
                log_activity('bulk_publish_post', 'post', $id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/posts')->with('message', "$count post(s) published.");
    }

    /**
     * Get unique slug by auto-incrementing if duplicate
     * 
     * @param string $slug Base slug
     * @param int|null $excludeId Post ID to exclude from check (for update)
     * @return string Unique slug
     */
    private function getUniqueSlug($slug, $excludeId = null)
    {
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $builder = $this->postModel->where('slug', $slug);
            
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            
            $existing = $builder->first();
            
            if (!$existing) {
                break;
            }
            
            // Slug exists, increment
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
