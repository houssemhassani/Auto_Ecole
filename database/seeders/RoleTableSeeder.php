<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::create([
            'name' =>'Hassani Houssem',
            'email' =>'houssemhassani@gmail.com',
            'password' =>Hash::make('06996868'),
            'level' =>'admin'
        ]);
        $role=Role::create(['name'=>'admin']);
        $permissions=Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
        //
    }
}
