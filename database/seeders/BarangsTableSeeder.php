<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangsTableSeeder extends Seeder
{
    public function run()
    {
        $barangs = [
            [
                'nama_barang' => 'JELLY 1 DUA 3',
                'jenis' => 'DUS',
                'nomer_rak' => '51241124',
                'sku' => 'KAKAKA',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM A',
                'jenis' => 'PCS',
                'nomer_rak' => '12345678',
                'sku' => 'ITEMA123',
                'pemilik' => '3',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM B',
                'jenis' => 'CON',
                'nomer_rak' => '23456789',
                'sku' => 'ITEMB234',
                'pemilik' => '4',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM C',
                'jenis' => 'DUS',
                'nomer_rak' => '34567890',
                'sku' => 'ITEMC345',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM D',
                'jenis' => 'PCS',
                'nomer_rak' => '45678901',
                'sku' => 'ITEMD456',
                'pemilik' => '2',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('barangs')->insert($barangs);
    }
}
