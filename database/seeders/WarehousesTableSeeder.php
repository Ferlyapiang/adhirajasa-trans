<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehousesTableSeeder extends Seeder
{
    public function run()
    {
        $warehouses = [
            [
                'name' => 'Warehouse A',
                'address' => 'Jl. A No. 1, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse B',
                'address' => 'Jl. B No. 2, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse C',
                'address' => 'Jl. C No. 3, Jakarta',
                'status' => 'inactive'
            ],
            [
                'name' => 'Warehouse D',
                'address' => 'Jl. D No. 4, Jakarta',
                'status' => 'active'
            ],
        ];

        DB::table('warehouses')->insert($warehouses);
    }
}
