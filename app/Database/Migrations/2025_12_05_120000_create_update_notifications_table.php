<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUpdateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type' => "ENUM('info', 'warning', 'error', 'success')",
                'default' => 'info',
                'null' => false,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'module' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'is_read' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('update_notifications');
    }

    public function down()
    {
        $this->forge->dropTable('update_notifications');
    }
}
