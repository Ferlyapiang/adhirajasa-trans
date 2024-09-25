<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    public function run()
    {
        DB::table('type_mobil')->insert([
            [
                'type' => 'Tronton',
                'rental_price' => 1500000.00,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => '20Ft',
                'rental_price' => 1200000.00,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => '40Ft',
                'rental_price' => 1800000.00,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => '45Ft',
                'rental_price' => 2000000.00,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
