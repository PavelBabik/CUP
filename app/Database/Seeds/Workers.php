<?php

namespace App\Database\Seeds;

use App\Entities\WorkerEntity;
use CodeIgniter\Database\Seeder;

class Workers extends Seeder
{
    public function run()
    {
        $data = [
            'id' => '1',
            'username' => 'test',
            'name' => 'name',
            'phone' => '+380999999999',
            'avatar' => 'sad.jpg',
            'status' => WorkerEntity::STATUS_NEW,
            'email' => 'lexssssss@gmail.com',
            'balance' => 0.00,
            'role' => WorkerEntity::ROLE_SUPER_ADMIN,
            'password' => sha1('test'),
            'password_reset_token' => '',
            'scope' => 'app',
        ];

        $this->db->table('workers')->insert($data);
    }
}
