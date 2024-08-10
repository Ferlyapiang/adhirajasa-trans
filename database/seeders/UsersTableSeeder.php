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
            'name' => 'Admin',
            'email' => 'admin@ats.com',
            'password' => Hash::make('admin123'),
            'status' => 'active',
            'group_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
