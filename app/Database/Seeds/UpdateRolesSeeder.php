<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateRolesSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('roles');

        // Update role 'staff' menjadi 'operator'
        $builder->where('name', 'staff')
                ->update([
                    'name' => 'operator',
                    'description' => 'Operator with full access except user management'
                ]);

        // Update description untuk role lainnya
        $builder->where('name', 'admin')
                ->update([
                    'description' => 'Administrator with full system access'
                ]);

        $builder->where('name', 'guru')
                ->update([
                    'description' => 'Teacher with access to posts and galleries only'
                ]);

        echo "✅ Roles updated successfully!\n";
        echo "- staff → operator (full access except users)\n";
        echo "- guru → access to posts & galleries only\n";
    }
}
