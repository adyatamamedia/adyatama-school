<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryItemModel extends Model
{
    protected $table            = 'gallery_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'gallery_id', 'media_id', 'type', 'path', 
        'caption', 'order_num'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';
}
