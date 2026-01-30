<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'FreshEgg Supplies',
                'contact_person' => 'Mark Santos',
                'email' => 'mark.santos@freshegg.com',
                'phone_number' => '09171234567',
                'address' => 'Blk 12 Lot 7, San Isidro, Quezon City',
            ],
            [
                'name' => 'MetroTools & Hardware',
                'contact_person' => 'Ana Dela Cruz',
                'email' => 'ana.delacruz@metrotools.ph',
                'phone_number' => '09983456721',
                'address' => 'Grace Village, Balintawak, QC',
            ],
            [
                'name' => 'PrimeSteel Industrial',
                'contact_person' => 'Jose Ramirez',
                'email' => 'jose.ramirez@primesteel.ph',
                'phone_number' => '09051239876',
                'address' => 'Rizal Ave., Caloocan City',
            ],
            [
                'name' => 'GreenLeaf Packaging',
                'contact_person' => 'Lara Mendoza',
                'email' => 'lara@greenleafpack.com',
                'phone_number' => '09284561234',
                'address' => 'Malate, Manila',
            ],
            [
                'name' => 'TechnoParts Supply Co.',
                'contact_person' => 'Rico Villanueva',
                'email' => 'rico@technoparts.com',
                'phone_number' => '09193456780',
                'address' => 'Mandaluyong City',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}

