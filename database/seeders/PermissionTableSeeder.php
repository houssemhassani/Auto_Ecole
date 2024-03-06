<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions=[
            'modul-role',
            'candidat-edit',
            'candidat-delete',
            'candidat-create',
            'candidat-list',
            'user-edit',
            'user-list',
            'user-create',
            'user-delete',
            'monitor-create',
            'monitor-delete',
            'monitor-edit',
            'monitor-list'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' =>$permission]);
        }
        //
    }
}
