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
            "first_name"=> "Alice",
            "last_name"=> "Wanda",
            "department_id"=>3
        ]);

        Employee::create([
            "first_name"=> "Monika",
            "last_name"=> "Monique",
            "department_id"=>2
        ]);

        Employee::create([
            "first_name"=> "Janna",
            "last_name"=> "Guerrero",
            "department_id"=>1
        ]);
    }
}
