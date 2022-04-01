<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MessagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'man_id' => [
                'type'       => 'INT',
                'constraint' => 255,
            ],
            'profile_id' => [
                'type'       => 'INT',
                'constraint' => 255,
            ],
            'in' => [
                'type' => 'BOOLEAN',
            ],
            'text' => [
                'type'       => 'VARCHAR',
                'constraint' => 500
            ],
            'time' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('messages');
    }

    public function down()
    {
        $this->forge->dropTable('messages');
    }
}
