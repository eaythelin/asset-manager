<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create role
        $systemSupervisorRole = Role::create(["name" => "System Supervisor"]);
        $deptHeadRole = Role::create(["name" => "Department Head"]);
        $generalManagerRole = Role::create(["name" => "General Manager"]);

        //Permission list [to be expanded on!]
        $crudPermissions = [
            'manage assets', 
            'manage departments',
            'manage users',
            'manage employees',
            'manage categories',
            'manage sub-categories',
            'manage suppliers',
            'manage assets',
            'manage reports',
            'manage workorders'
        ];

        $requestPermissions = [
            'approve requests',
            'decline requests',
            'submit requests',
            'create requests'
        ];

        $viewPermissions = [
            'view assets',
            'view departments',
            'view users',
            'view employees',
            'view configs',
            'view dashboard',
            'view categories',
            'view sub-categories',
            'view assets',
            'view suppliers',
            'view reports',
            'view requests',
            'view workorders'
        ];

        foreach($crudPermissions as $crudPermission){
            Permission::firstOrCreate(["name" => $crudPermission]);
        }

        foreach($viewPermissions as $viewPermission){
            Permission::firstOrCreate(["name" => $viewPermission]);
        }

        foreach($requestPermissions as $requestPermission){
            Permission::firstOrCreate(["name" => $requestPermission]);
        }

        //Assigning permissions
        $systemSupervisorPerms = [
            'manage assets', 
            'manage departments',
            'manage users',
            'manage employees',
            'manage categories',
            'manage sub-categories',
            'manage assets',
            'manage suppliers',
            'manage reports',
            'manage workorders',
            'view departments',
            'view users',
            'view employees',
            'view configs',
            'view dashboard',
            'view categories',
            'view sub-categories',
            'view assets',
            'view suppliers',
            'view reports',
            'view requests',
            'view workorders'
        ];

        foreach($systemSupervisorPerms as $perms){
            $systemSupervisorRole->givePermissionTo($perms);
        }

        $deptHeadPerms = [
            'view assets',
            'view employees',
            'view dashboard',
            'view requests',
            'submit requests',
            'create requests'
        ];

        foreach($deptHeadPerms as $perms){
            $deptHeadRole->givePermissionTo($perms);
        }

        $generalManagerPerms = [
            'view assets',
            'view employees',
            'view dashboard',
            'view requests',
            'approve requests',
            'decline requests',
        ];

        foreach($generalManagerPerms as $perms){
            $generalManagerRole->givePermissionTo($perms);
        }
    }
}
