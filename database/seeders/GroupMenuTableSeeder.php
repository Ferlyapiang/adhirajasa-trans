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

        // Fetch only menu IDs where parent_id is null
        $menuIds = DB::table('menus')
            ->whereNull('parent_id') // Filter menus where parent_id is null
            ->pluck('id')
            ->toArray();

        // Link admin group with all menus where parent_id is null
        foreach ($menuIds as $menuId) {
            DB::table('group_menu')->insert([
                'group_id' => $adminGroupId,
                'menu_id' => $menuId,
            ]);
        }

        // Link warehouse group with specific menus where parent_id is null
        DB::table('group_menu')->insert([
            ['group_id' => $warehouseGroupId, 'menu_id' => 1], // Example: Dashboard
            ['group_id' => $warehouseGroupId, 'menu_id' => 2], // Example: Reports
            // Add more specific menus as needed for this group
        ]);

        // Link operation group with specific menus where parent_id is null
        DB::table('group_menu')->insert([
            ['group_id' => $operationGroupId, 'menu_id' => 1], // Example: Dashboard
            // Add more specific menus as needed for this group
        ]);

        // Add more groups and their menu links as needed
    }
}
