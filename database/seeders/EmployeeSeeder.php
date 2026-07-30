<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            "name"=> "Alice Wanda",
            "department_id"=>3,
            'employee_no' => 'EMP-0001'
        ]);

        Employee::create([
            "name"=> "Monika Monique",
            "department_id"=>2,
            'employee_no' => 'EMP-0002'
        ]);

        Employee::create([
            "name"=> "Janna Guerrero",
            "department_id"=>1,
            'employee_no' => 'EMP-0003'
        ]);

         Employee::create([
            "name"=> "James Venom",
            "department_id"=>3,
            'employee_no' => 'EMP-0004'
        ]);

        Employee::create([
            "name"=> "Victor Chill",
            "department_id"=>3,
            'employee_no' => 'EMP-0005'
        ]);
    }
}
