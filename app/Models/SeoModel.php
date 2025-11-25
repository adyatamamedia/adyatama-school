<?php

namespace App\Models;

use CodeIgniter\Model;

class SeoModel extends Model
{
    protected $table            = 'seo_overrides';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'subject_type', 'subject_id', 'meta_title', 'meta_description', 
        'meta_keywords', 'canonical', 'robots', 'structured_data'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Helper to get SEO data for a subject
    public function getSeo($type, $id)
    {
        return $this->where('subject_type', $type)
                    ->where('subject_id', $id)
                    ->first();
    }
}
