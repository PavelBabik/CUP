<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Profiles extends Seeder
{
    public function run()
    {
        $data = [
            'id'        => '1',
            'site_id'   => 2182907,
            'name'      => '',
            'site_pass' => '',
        ];

        $this->db->table('users')->insert($data);
    }
}
