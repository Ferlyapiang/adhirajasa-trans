<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankDatasTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are some warehouses in the database for the foreign key constraint
        $warehouses = DB::table('warehouses')->pluck('id')->toArray();
        
        $bankDatas = [
            [
                'bank_name' => 'Bank A',
                'account_number' => '1234567890',
                'account_name' => 'Account A',
                'warehouse_id' => $warehouses[array_rand($warehouses)], // Pick a random warehouse ID
                'status' => 'active'
            ],
            [
                'bank_name' => 'Bank B',
                'account_number' => '0987654321',
                'account_name' => 'Account B',
                'warehouse_id' => $warehouses[array_rand($warehouses)], // Pick a random warehouse ID
                'status' => 'active'
            ],
            [
                'bank_name' => 'Bank C',
                'account_number' => '1122334455',
                'account_name' => 'Account C',
                'warehouse_id' => $warehouses[array_rand($warehouses)], // Pick a random warehouse ID
                'status' => 'inactive'
            ],
            [
                'bank_name' => 'Bank D',
                'account_number' => '5566778899',
                'account_name' => 'Account D',
                'warehouse_id' => $warehouses[array_rand($warehouses)], // Pick a random warehouse ID
                'status' => 'active'
            ],
        ];

        DB::table('bank_datas')->insert($bankDatas);
    }
}
