<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@ats.com',
                'password' => Hash::make('admin123'),
                'status' => 'active',
                'group_id' => 1,
                'warehouse_id' => null,  // Add this line to match the column count
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin kapuk',
                'email' => 'adminkapuk@ats.com',
                'password' => Hash::make('admin123'),
                'status' => 'active',
                'group_id' => 1,
                'warehouse_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
