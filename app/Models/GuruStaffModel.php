<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruStaffModel extends Model
{
    protected $table            = 'guru_staff';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nip', 'nama_lengkap', 'jabatan', 'bidang', 
        'email', 'no_hp', 'foto', 'status', 'is_active', 'user_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_lengkap' => 'required|min_length[3]|max_length[150]',
        'status'       => 'required|in_list[guru,staff]',
        'email'        => 'permit_empty|valid_email',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
