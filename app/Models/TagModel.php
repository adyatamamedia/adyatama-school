<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table            = 'tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['post_id', 'name', 'slug'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; 

    // Validation (Loose for now as tags are often ad-hoc, but good to have name)
    protected $validationRules      = [
        'name' => 'required|min_length[2]|max_length[100]',
    ];
}
