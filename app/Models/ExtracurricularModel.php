<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtracurricularModel extends Model
{
    protected $table            = 'extracurriculars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at in schema
}
