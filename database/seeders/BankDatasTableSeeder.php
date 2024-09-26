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
                'bank_name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'Sherlyn',
                'warehouse_id' => 1,
                'status' => 'active'
            ],
            [
                'bank_name' => 'Bank BNI',
                'account_number' => '0987654321',
                'account_name' => 'John Doe',
                'warehouse_id' => 2,
                'status' => 'active'
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1122334455',
                'account_name' => 'Richo',
                'warehouse_id' => 3,
                'status' => 'inactive'
            ],
            [
                'bank_name' => 'Bank BRI',
                'account_number' => '5566778899',
                'account_name' => 'Edward',
                'warehouse_id' => 4,
                'status' => 'active'
            ],
        ];

        DB::table('bank_datas')->insert($bankDatas);
    }
}
