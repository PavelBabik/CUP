<?php

namespace App\Database\Seeds;

use App\Entities\WorkerEntity;
use CodeIgniter\Database\Seeder;

class Cities extends Seeder
{
    public function run()
    {
        $data[] = ['name' => 'Kiev'];
        $data[] = ['name' => 'Odessa (Солнечная)'];
        $data[] = ['name' => 'Odessa (Балковская)'];
        $data[] = ['name' => 'Odessa'];
        $data[] = ['name' => 'Lviv'];
        $data[] = ['name' => 'Izmail'];
        $data[] = ['name' => 'Prague'];
        $data[] = ['name' => 'Charkiv'];
        $data[] = ['name' => 'Sumi'];
        $data[] = ['name' => 'Uman'];
        $data[] = ['name' => 'Mumbai'];


        $this->db->table('cities')->insertBatch($data);
    }
}
