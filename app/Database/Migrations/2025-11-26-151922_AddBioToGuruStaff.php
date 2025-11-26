<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBioToGuruStaff extends Migration
{
    public function up()
    {
        $fields = [
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'no_hp'
            ]
        ];

        $this->forge->addColumn('guru_staff', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('guru_staff', 'bio');
    }
}
