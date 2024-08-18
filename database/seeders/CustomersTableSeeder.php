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
                'no_npwp_ktp' => '1234567890123456',
                'no_hp' => '08123456789',
                'email' => 'johndoe@example.com',
                'address' => 'Jl. Example No. 1, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'no_npwp_ktp' => '2345678901234567',
                'no_hp' => '08234567890',
                'email' => 'janesmith@example.com',
                'address' => 'Jl. Example No. 2, Jakarta',
                'status' => 'active'
            ],
            [
                'name' => 'Alice Johnson',
                'no_npwp_ktp' => '3456789012345678',
                'no_hp' => '08345678901',
                'email' => 'alicejohnson@example.com',
                'address' => 'Jl. Example No. 3, Jakarta',
                'status' => 'inactive'
            ],
            [
                'name' => 'Bob Brown',
                'no_npwp_ktp' => '4567890123456789',
                'no_hp' => '08456789012',
                'email' => 'bobbrown@example.com',
                'address' => 'Jl. Example No. 4, Jakarta',
                'status' => 'active'
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
