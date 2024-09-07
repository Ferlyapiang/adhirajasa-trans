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
                'phone_number' => '081234567890',
                'email' => 'warehouseA@example.com',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse B',
                'address' => 'Jl. B No. 2, Jakarta',
                'phone_number' => '081234567891',
                'email' => 'warehouseB@example.com',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse C',
                'address' => 'Jl. C No. 3, Jakarta',
                'phone_number' => '081234567892',
                'email' => 'warehouseC@example.com',
                'status' => 'inactive'
            ],
            [
                'name' => 'Warehouse D',
                'address' => 'Jl. D No. 4, Jakarta',
                'phone_number' => '081234567893',
                'email' => 'warehouseD@example.com',
                'status' => 'active'
            ],
        ];

        DB::table('warehouses')->insert($warehouses);
    }
}
