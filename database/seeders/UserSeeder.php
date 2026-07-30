<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch roles
        $systemSupervisorRole = Role::where('name', 'System Supervisor')->first();
        $deptHeadRole = Role::where('name', 'Department Head')->first();
        $generalManagerRole = Role::where('name', 'General Manager')->first();
        $maintenanceCrewRole = Role::where('name', 'Maintenance Crew')->first();

        // Users (replace with test emails or env vars later)
        $users = [
            [
                "name" => "Janna Guerrero",
                "email" => "admin@example.com",
                "password" => Hash::make('password123'),
                "employee_id" => 1,
                "role" => $systemSupervisorRole,
            ],
            [
                "name" => "Monika Monique",
                "email" => "depthead@example.com",
                "password" => Hash::make('password123'),
                "employee_id" => 2,
                "role" => $deptHeadRole,
            ],
            [
                "name" => "Alice Wanda",
                "email" => "manager@example.com",
                "password" => Hash::make('password123'),
                "employee_id" => 3,
                "role" => $generalManagerRole,
            ],
            [
                "name" => "James Venom",
                "email" => "jamesvenom@example.com",
                "password" => Hash::make('password123'),
                "employee_id" => 4,
                "role" => $maintenanceCrewRole,
            ],
            [
                "name" => "Victor Chill",
                "email" => "victorchill@example.com",
                "password" => Hash::make('password123'),
                "employee_id" => 5,
                "role" => $maintenanceCrewRole,
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'employee_id' => $data['employee_id'],
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}

