<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypesTableSeeder extends Seeder
{
    public function run()
    {
        $itemTypes = [
            ['name' => 'DUS', 'status' => 'active'],
            ['name' => 'Qubic', 'status' => 'active'],
            ['name' => 'PCS', 'status' => 'active'],
            ['name' => 'CON', 'status' => 'active'],
        ];

        DB::table('item_types')->insert($itemTypes);
    }
}
