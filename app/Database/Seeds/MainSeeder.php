<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'guru', 'description' => 'Teacher with content management access'],
            ['name' => 'staff', 'description' => 'Staff with limited access'],
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('roles');

        foreach ($roles as $role) {
            // Check if exists
            if ($builder->where('name', $role['name'])->countAllResults() === 0) {
                $builder->insert($role);
            }
        }

        // Get Admin Role ID
        $adminRole = $builder->where('name', 'admin')->get()->getRow();

        // 2. Seed Admin User
        $userBuilder = $db->table('users');
        $username = 'admin';
        
        if ($userBuilder->where('username', $username)->countAllResults() === 0) {
            $data = [
                'username'      => $username,
                'email'         => 'admin@adyatama.school',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'fullname'      => 'Super Admin',
                'role_id'       => $adminRole->id,
                'status'        => 'active',
                'created_at'    => date('Y-m-d H:i:s'),
            ];
            $userBuilder->insert($data);
            echo "Admin user created (user: admin, pass: password123)\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}
