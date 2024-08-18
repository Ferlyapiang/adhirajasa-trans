<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(WarehousesTableSeeder::class);
        $this->call(ItemTypesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(BankDatasTableSeeder::class);
        $this->call(BarangsTableSeeder::class);
    }

}
