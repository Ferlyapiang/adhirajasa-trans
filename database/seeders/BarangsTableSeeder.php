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
                'sku' => 'KAKAKA',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM A',
                'jenis' => 'PCS',
                'sku' => 'ITEMA123',
                'pemilik' => '3',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM B',
                'jenis' => 'CON',
                'sku' => 'ITEMB234',
                'pemilik' => '4',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM C',
                'jenis' => 'DUS',
                'sku' => 'ITEMC345',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'ITEM D',
                'jenis' => 'PCS',
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
