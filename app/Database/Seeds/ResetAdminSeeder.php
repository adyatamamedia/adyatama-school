<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResetAdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        // Delete existing admin
        $builder->where('username', 'admin')->delete();
        echo "Admin user deleted.\n";

        // Get Admin Role ID
        $roleBuilder = $db->table('roles');
        $adminRole = $roleBuilder->where('name', 'admin')->get()->getRow();

        if (!$adminRole) {
            echo "Admin role not found. Run MainSeeder first.\n";
            return;
        }

        // Re-create Admin User
        $data = [
            'username'      => 'admin',
            'email'         => 'admin@adyatama.school',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'fullname'      => 'Super Admin',
            'role_id'       => $adminRole->id,
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        $builder->insert($data);
        echo "Admin user recreated (user: admin, pass: password123)\n";
    }
}
