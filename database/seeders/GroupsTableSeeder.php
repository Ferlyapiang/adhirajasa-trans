<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Warehouse', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guest', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
