<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::create([
            "asset_code" => 'AST-1',
            "name" => 'Helixcore Laptop',
            "serial_name" => 'HelixCore M52',
            "description" => 'Brand new laptop for Maintenance Department',
            "acquisition_date" => fake()->date(),
            "category_id" => 1,
            "department_id" => 3,
            "sub_category_id" => 2,
            "supplier_id" => 1,
            "custodian_id" => 1,
        ]);

        Asset::create([
            "asset_code" => 'AST-2',
            "name" => 'AstraCore PC',
            "serial_name" => 'AstraCore M45',
            "description" => 'PC for Admin',
            "acquisition_date" => fake()->date(),
            "category_id" => 1,
            "department_id" => 2,
            "sub_category_id" => 1,
            "supplier_id" => 1,
            "custodian_id" => 2,
        ]);

        Asset::create([
            "asset_code" => 'AST-3',
            "name" => 'Office Table',
            "description" => 'Table for Admin',
            "acquisition_date" => fake()->date(),
            "category_id" => 2,
            "department_id" => 2,
            "sub_category_id" => 4,
            "supplier_id" => 1,
            "custodian_id" => 2,
        ]);

        Asset::create([
            "asset_code" => 'AST-4',
            "name" => 'Office Chair',
            "description" => 'Chair for Admin',
            "acquisition_date" => fake()->date(),
            "category_id" => 2,
            "department_id" => 2,
            "sub_category_id" => 4,
            "supplier_id" => 1,
            "custodian_id" => 2,
        ]);
    }
}
