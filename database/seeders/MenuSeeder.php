<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan menu utama dengan priority
        $dashboardId = DB::table('menus')->insertGetId([
            'name' => 'Home',
            'url' => '/dashboard',
            'router' => null,
            'icon' => 'fa fa-home',
            'is_active' => 1,
            'priority' => 1, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managementMenuId = DB::table('menus')->insertGetId([
            'name' => 'Management Menu',
            'url' => '/management-menu/*',
            'router' => null,
            'icon' => 'fa fa-book',
            'is_active' => 1,
            'priority' => 2, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $managementUserId = DB::table('menus')->insertGetId([
            'name' => 'Management User',
            'url' => '/management-user/*',
            'router' => null,
            'icon' => 'fa fa-users',
            'is_active' => 1,
            'priority' => 2, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $logId = DB::table('menus')->insertGetId([
            'name' => 'Log',
            'url' => '/log/*',
            'router' => null,
            'icon' => 'fa fa-chart-pie',
            'is_active' => 1,
            'priority' => 3, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $masterDataId = DB::table('menus')->insertGetId([
            'name' => 'Master Data',
            'url' => '/master-data/*',
            'router' => null,
            'icon' => 'fa fa-list-alt',
            'is_active' => 1,
            'priority' => 4, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dataGudangId = DB::table('menus')->insertGetId([
            'name' => 'Data Gudang',
            'url' => '/data-gudang/*',
            'router' => null,
            'icon' => 'fa fa-cubes',
            'is_active' => 1,
            'priority' => 5, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dataInvoiceId = DB::table('menus')->insertGetId([
            'name' => 'Data Invoice',
            'url' => '/data-invoice/*',
            'router' => null,
            'icon' => 'fa fa-university',
            'is_active' => 1,
            'priority' => 6, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $dataReportingId = DB::table('menus')->insertGetId([
            'name' => 'Reporting Invoice',
            'url' => '/data-reporting-invoice/*',
            'router' => null,
            'icon' => 'fas fa-archway',
            'is_active' => 1,
            'priority' => 7, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dataBongkarMuat = DB::table('menus')->insertGetId([
            'name' => 'Data Bongkar Muat',
            'url' => '/data-bongkar-muat/*',
            'router' => null,
            'icon' => 'fas fa-chalkboard-teacher',
            'is_active' => 1,
            'priority' => 8, // Priority
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('menus')->insert([
            [
                'name' => 'Menu',
                'url' => '/management-menu/menus',
                'router' => 'management-user.users.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $managementMenuId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('menus')->insert([
            [
                'name' => 'Group Menu',
                'url' => '/management-menu/group_menu',
                'router' => 'management-user.users.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 2, // Priority untuk submenu
                'parent_id' => $managementMenuId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Menambahkan sub-menu untuk Management User
        DB::table('menus')->insert([
            [
                'name' => 'User',
                'url' => '/management-user/users',
                'router' => 'management-user.users.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $managementUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        

        // Menambahkan sub-menu untuk Log
        DB::table('menus')->insert([
            [
                'name' => 'Data Logs',
                'url' => '/log/reports-log',
                'router' => 'reports.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $logId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Menambahkan sub-menu untuk Master Data
        DB::table('menus')->insert([
            [
                'name' => 'Data Customer',
                'url' => '/master-data/customers',
                'router' => 'master-data.customers.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tipe Barang',
                'url' => '/master-data/item-types',
                'router' => 'master-data.item-types.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 2,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Bank',
                'url' => '/master-data/bank-data',
                'router' => 'master-data.bank-data.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 3,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Gudang',
                'url' => '/master-data/warehouses',
                'router' => 'master-data.warehouses.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 4,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Barang',
                'url' => '/master-data/barang',
                'router' => 'master-data.barang.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 5,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Jenis Mobil',
                'url' => '/master-data/jenis-mobil',
                'router' => 'master-data.jenis-mobil.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 6,
                'parent_id' => $masterDataId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Menambahkan sub-menu untuk Data Gudang
        DB::table('menus')->insert([
            [
                'name' => 'Barang Masuk',
                'url' => '/data-gudang/barang-masuk',
                'router' => 'data-gudang.barang-masuk.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $dataGudangId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Barang Keluar',
                'url' => '/data-gudang/barang-keluar',
                'router' => 'data-gudang.barang-keluar.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 2, // Priority untuk submenu
                'parent_id' => $dataGudangId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('menus')->insert([
            [
                'name' => 'Invoice Barang Masuk',
                'url' => '/data-invoice/invoice-masuk',
                'router' => 'data-invoice.invoice-masuk.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $dataInvoiceId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Invoice Barang Keluar',
                'url' => '/data-invoice/invoice-keluar',
                'router' => 'data-invoice.invoice-keluar.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 2, // Priority untuk submenu
                'parent_id' => $dataInvoiceId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Invoice Master',
                'url' => '/data-invoice/invoice-master',
                'router' => 'data-invoice.invoice-master.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 3, // Priority untuk submenu
                'parent_id' => $dataInvoiceId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('menus')->insert([
            [
                'name' => 'Reporting Invoice',
                'url' => '/data-reporting-invoice/invoice-reporting',
                'router' => 'data-invoice.invoice-reporting.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $dataReportingId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('menus')->insert([
            [
                'name' => 'Reporting Invoice',
                'url' => '/data-reporting-invoice/invoice-reporting',
                'router' => 'data-invoice.invoice-reporting.index',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $dataReportingId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('menus')->insert([
            [
                'name' => 'Report Bongkar Muat',
                'url' => '/data-bongkar-muat/reporting-data',
                'router' => 'data-bongkar-muat.getData',
                'icon' => 'far fa-circle',
                'is_active' => 1,
                'priority' => 1, // Priority untuk submenu
                'parent_id' => $dataReportingId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
