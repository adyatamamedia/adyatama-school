<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table            = 'media';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // Soft delete enabled per schema
    protected $protectFields    = true;
    protected $allowedFields    = [
        'origin_post_id', 'type', 'path', 'caption', 
        'meta', 'order_num', 'filesize'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at in schema for media
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'path' => 'required|max_length[255]',
        'type' => 'required|in_list[image,video,file]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
