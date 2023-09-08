<?php

namespace Database\Seeders;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $users = User::all();
        $rc = count($roles);
        // GENERATE A USER WITH ALL PRIVILEGES?

        foreach($users as $us) {
            $roleid = Role::inRandomOrder()->first()->id;
            if ($roleid > 4) {
                $us->roles()->attach($roleid);
                $us->roles()->attach(4);
            } else {
                $last = 4;
                if ($roleid == 1) {
                    $last = $rc;
                }
                for($i = $roleid; $i <= $last; $i++) {
                    $us->roles()->attach($i);
                }
            }
        }
    }
}
