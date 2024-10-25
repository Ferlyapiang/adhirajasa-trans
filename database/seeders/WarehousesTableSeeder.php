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
                'name' => 'Warehouse Ancol',
                'address' => 'Jl. A No. 1, Jakarta',
                'initial' => 'ACL',
                'phone_number' => '081234567890',
                'email' => 'warehouseA@example.com',
                'status_office' => 'branch_office',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse Kapuk',
                'initial' => 'KPK',
                'address' => 'Jl. B No. 2, Jakarta',
                'phone_number' => '081234567891',
                'email' => 'warehouseB@example.com',
                'status_office' => 'head_office',
                'status' => 'active'
            ],
            [
                'name' => 'Warehouse Angke',
                'initial' => 'AGE',
                'address' => 'Jl. C No. 3, Jakarta',
                'phone_number' => '081234567892',
                'email' => 'warehouseC@example.com',
                'status_office' => 'branch_office',
                'status' => 'inactive'
            ],
            [
                'name' => 'Warehouse Muara',
                'initial' => 'MRA',
                'address' => 'Jl. D No. 4, Jakarta',
                'phone_number' => '081234567893',
                'email' => 'warehouseD@example.com',
                'status_office' => 'branch_office',
                'status' => 'active'
            ],
        ];

        DB::table('warehouses')->insert($warehouses);
    }
}
