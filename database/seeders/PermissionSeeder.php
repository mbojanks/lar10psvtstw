<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TO BE REVISED!!
        Permission::insert([
            ["name" => 'view_users'],
            ["name" => 'edit_users'],
            ["name" => 'view_roles'],
            ["name" => 'edit_roles'],
            /*["name" => 'view_conferences'],
            ["name" => 'edit_conferences'],
            ["name" => 'view_conferenceseries'],
            ["name" => 'edit_conferenceseries'],*/
        ]);
    }
}
