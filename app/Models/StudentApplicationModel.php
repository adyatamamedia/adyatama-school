<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentApplicationModel extends Model
{
    protected $table = 'student_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat', 'nama_ortu', 'no_hp', 'email', 'asal_sekolah',
        'dokumen_kk', 'dokumen_akte', 'pas_foto', 'foto_ijazah', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
}
