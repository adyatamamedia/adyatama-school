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
        // Group by name since tags are per-post in schema
        // In a normalized schema, tags would be a separate table + pivot.
        // Here 'tags' table has 'post_id'. This means one tag row per post.
        // So listing unique tags requires grouping.
        
        // DB Schema: id, post_id, name, slug.
        // This schema implies a tag is strictly bound to a post.
        // To manage "Tags" generally, we can list unique tag names used across posts.
        
        $data = [
            'title' => 'Tags Overview',
            'tags' => $this->tagModel->select('name, slug, COUNT(id) as count')
                                     ->groupBy('name, slug')
                                     ->orderBy('count', 'DESC')
                                     ->findAll()
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
}
