<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'name_pt' => 'PT Example Corp',
                'no_npwp' => '1234567890123456', // Split into no_npwp
                'no_ktp' => '3201011234567890', // New field for no_ktp
                'no_hp' => '08123456789',
                'type_payment_customer' => 'Credit',
                'warehouse_id' => 'W001',
                'email' => 'johndoe@example.com',
                'address' => 'Jl. Example No. 1, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'name_pt' => 'PT Example Industries',
                'no_npwp' => '2345678901234567',
                'no_ktp' => '3202022345678901',
                'no_hp' => '08234567890',
                'type_payment_customer' => 'Cash',
                'warehouse_id' => 'W002',
                'email' => 'janesmith@example.com',
                'address' => 'Jl. Example No. 2, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Alice Johnson',
                'name_pt' => 'PT Alpha Tech',
                'no_npwp' => '3456789012345678',
                'no_ktp' => '3203033456789012',
                'no_hp' => '08345678901',
                'type_payment_customer' => 'Credit',
                'warehouse_id' => 'W003',
                'email' => 'alicejohnson@example.com',
                'address' => 'Jl. Example No. 3, Jakarta',
                'status' => 'inactive'
            ],
            [
                'name' => 'Bob Brown',
                'name_pt' => 'PT Beta Solutions',
                'no_npwp' => '4567890123456789',
                'no_ktp' => '3204044567890123',
                'no_hp' => '08456789012',
                'type_payment_customer' => 'Cash',
                'warehouse_id' => 'W004',
                'email' => 'bobbrown@example.com',
                'address' => 'Jl. Example No. 4, Jakarta',
                'status' => 'active'
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
