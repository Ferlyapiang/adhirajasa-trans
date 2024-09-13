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
                'nama_barang' => 'JELLY DUS',
                'jenis' => 'DUS',
                'sku' => 'KAKAKA',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'Permata Hijau PCS',
                'jenis' => 'PCS',
                'sku' => 'ITEMA123',
                'pemilik' => '3',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'Jelly CON',
                'jenis' => 'CON',
                'sku' => 'ITEMB234',
                'pemilik' => '4',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'Indomie DUS',
                'jenis' => 'DUS',
                'sku' => 'ITEMC345',
                'pemilik' => '2',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_barang' => 'Bizsco PCS',
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
