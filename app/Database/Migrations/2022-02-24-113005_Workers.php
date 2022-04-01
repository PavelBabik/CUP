<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Workers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'INT',
                'constraint' => 255,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'password_reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'status' => [
                'type'       => 'INT',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'scope' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'balance' => [
                'type'       => 'FLOAT',
            ],
            'role' => [
                'type'       => 'INT',
                'constraint' => 10,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('workers');
    }

    public function down()
    {
        $this->forge->dropTable('workers');
    }
}
