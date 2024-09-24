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
                'no_npwp' => '',
                'no_ktp' => '3201011234567890',
                'no_hp' => '08123456789',
                'type_payment_customer' => 'Pertanggal Masuk',
                'warehouse_id' => 1, // Use integer ID
                'email' => 'johndoe@example.com',
                'address' => 'Jl. Example No. 1, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'name_pt' => 'PT Example Industries',
                'no_npwp' => '',
                'no_ktp' => '3202022345678901',
                'no_hp' => '08234567890',
                'type_payment_customer' => 'Akhir Bulan',
                'warehouse_id' => 2, // Use integer ID
                'email' => 'janesmith@example.com',
                'address' => 'Jl. Example No. 2, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Alice Johnson',
                'name_pt' => 'PT Alpha Tech',
                'no_npwp' => '3456789012345678',
                'no_ktp' => '',
                'no_hp' => '08345678901',
                'type_payment_customer' => 'Pertanggal Masuk',
                'warehouse_id' => 3, // Use integer ID
                'email' => 'alicejohnson@example.com',
                'address' => 'Jl. Example No. 3, Jakarta',
                'status' => 'inactive'
            ],
            [
                'name' => 'Bob Brown',
                'name_pt' => 'PT Beta Solutions',
                'no_npwp' => '4567890123456789',
                'no_ktp' => '',
                'no_hp' => '08456789012',
                'type_payment_customer' => 'Akhir Bulan',
                'warehouse_id' => 4, // Use integer ID
                'email' => 'bobbrown@example.com',
                'address' => 'Jl. Example No. 4, Jakarta',
                'status' => 'active'
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
