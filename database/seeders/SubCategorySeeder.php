<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubCategory::create([
            'name' => 'Computers',
            'category_id' => 1,
            'description' => 'Desktop Computers'
        ]);

        SubCategory::create([
            'name' => 'Laptops',
            'category_id' => 1,
            'description' => 'Office Laptops'
        ]);

        SubCategory::create([
            'name' => 'Peripherals & Accessories',
            'category_id' => 1,
            'description' => 'Mouse, keyboards, mice and other IT accessories'
        ]);

        SubCategory::create([
            'name' => 'Office Furniture',
            'category_id' => 2,
            'description' => 'Desks, chairs, conference tables etc.'
        ]);

        SubCategory::create([
            'name' => 'Storage & Cabinets',
            'category_id' => 2,
            'description' => 'Filing cabinets, shelves, lockers etc.'
        ]);
    }
}
