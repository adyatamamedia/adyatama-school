<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'author_id', 'category_id', 'post_type', 'title', 'slug', 
        'excerpt', 'content', 'featured_media_id', 'video_url', 
        'view_count', 'react_enabled', 'comments_enabled', 
        'status', 'published_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'       => 'required|min_length[5]|max_length[255]',
        'slug'        => 'required|is_unique[posts.slug,id,{id}]|max_length[255]',
        'category_id' => 'permit_empty|is_not_unique[categories.id]',
        'status'      => 'in_list[draft,published]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
