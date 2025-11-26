<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TagModel;

class Tags extends BaseController
{
    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'count_desc';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query - Group by name since tags are per-post in schema
        $builder = $this->tagModel->select('name, slug, COUNT(id) as count, MAX(created_at) as latest_use')
                                  ->groupBy('name, slug');
        
        // Apply search
        if ($search) {
            $builder->having('name LIKE', "%{$search}%")
                    ->orHaving('slug LIKE', "%{$search}%");
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'count_asc':
                $builder->orderBy('count', 'ASC');
                break;
            case 'name_asc':
                $builder->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $builder->orderBy('name', 'DESC');
                break;
            case 'newest':
                $builder->orderBy('latest_use', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('latest_use', 'ASC');
                break;
            case 'count_desc':
            default:
                $builder->orderBy('count', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Tags Overview',
            'tags' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'count_desc' => 'Paling Banyak Digunakan',
                'count_asc' => 'Paling Sedikit Digunakan',
                'name_asc' => 'Nama A-Z',
                'name_desc' => 'Nama Z-A',
                'newest' => 'Terakhir Digunakan',
                'oldest' => 'Pertama Digunakan'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus tag dari semua post?']
            ],
            'createButton' => false // Tags are created via posts
        ];

        return view('admin/tags/index', $data);
    }
    
    // Since tags are tied to post_id, standalone CRUD is tricky without a pivot table.
    // Deleting a "Tag" here would imply deleting it from ALL posts using it.
    public function delete($slug)
    {
        $this->tagModel->where('slug', $slug)->delete();
        return redirect()->to('/dashboard/tags')->with('message', 'Tag usage removed from all posts.');
    }

    public function bulkDelete()
    {
        $slugs = $this->request->getPost('ids'); // ids here = slugs

        if (!$slugs || !is_array($slugs)) {
            return redirect()->back()->with('error', 'No tags selected.');
        }

        $count = 0;
        foreach ($slugs as $slug) {
            $deleted = $this->tagModel->where('slug', $slug)->delete();
            if ($deleted) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/tags')->with('message', "$count tag(s) removed from all posts.");
    }
}
