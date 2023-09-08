<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        $admin = Role::whereName('Admin')->first();
        foreach($permissions as $permission) {
            DB::table('permission_role')->insert([
                'role_id' => $admin->id,
                'permission_id' => $permission->id,
            ]);
        }
        $editor = Role::whereName('Editor')->first();
        foreach($permissions as $permission) {
            if(!in_array($permission->name,['edit_roles'])) {
                DB::table('permission_role')->insert([
                    'role_id' => $editor->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
    // REFLECT ON THIS FURTHER!!
}
