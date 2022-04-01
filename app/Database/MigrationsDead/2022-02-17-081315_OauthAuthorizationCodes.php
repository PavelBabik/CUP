<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OauthAuthorizationCodes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'authorization_code' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'client_id' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'redirect_uri' => [
                'type' => 'VARCHAR',
                'constraint' => 2000,
            ],
            'expires' => [
                'type' => 'TIMESTAMP',
            ],
            'scope' => [
                'type' => 'VARCHAR',
                'constraint' => 4000,
            ],
            'id_token' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
            ],
        ]);
        $this->forge->addKey('authorization_code', true);
        $this->forge->createTable('oauth_authorization_codes');
    }

    public function down()
    {
        $this->forge->dropTable('oauth_authorization_codes');
    }
}

