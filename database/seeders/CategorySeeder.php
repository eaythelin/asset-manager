<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'IT Equipment',
            'description' => "Electronic devices and hardware"
        ]);

        Category::create([
            'name' => 'Furniture',
            'description' => 'Office and workplace furnitures'
        ]);
    }
}
