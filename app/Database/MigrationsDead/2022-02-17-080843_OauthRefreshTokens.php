<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OauthRefreshTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'refresh_token' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
            ],
            'client_id' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'expires' => [
                'type' => 'TIMESTAMP',
                'constraint' => 80,
            ],
            'scope' => [
                'type' => 'VARCHAR',
                'constraint' => 4000,
            ],
        ]);
        $this->forge->addKey('refresh_token', true);
        $this->forge->createTable('oauth_refresh_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('oauth_refresh_tokens');
    }
}

