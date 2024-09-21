<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupMenuTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch the IDs of the groups
        $adminGroupId = DB::table('groups')->where('name', 'admin')->value('id');
        $warehouseGroupId = DB::table('groups')->where('name', 'Warehouse')->value('id');
        $operationGroupId = DB::table('groups')->where('name', 'Operation')->value('id');

        // Fetch all menu IDs
        $menuIds = DB::table('menus')->pluck('id')->toArray();

        // Link admin group with all menus
        foreach ($menuIds as $menuId) {
            DB::table('group_menu')->insert([
                'group_id' => $adminGroupId,
                'menu_id' => $menuId,
            ]);
        }

        // Link warehouse group with specific menus (e.g., Dashboard only)
        DB::table('group_menu')->insert([
            ['group_id' => $warehouseGroupId, 'menu_id' => 1], // Dashboard
            ['group_id' => $warehouseGroupId, 'menu_id' => 2], // Reports
            // Add more menus as needed for this group
        ]);

        // Link operation group with specific menus
        DB::table('group_menu')->insert([
            ['group_id' => $operationGroupId, 'menu_id' => 1], // Dashboard
            // Add more menus as needed for this group
        ]);

        // Add more groups and their menu links as needed
    }
}
