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
            "department_id"=>3
        ]);

        Employee::create([
            "name"=> "Monika Monique",
            "department_id"=>2
        ]);

        Employee::create([
            "name"=> "Janna Guerrero",
            "department_id"=>1
        ]);
    }
}
