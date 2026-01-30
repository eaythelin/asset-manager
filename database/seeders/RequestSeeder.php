<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\RequestStatus;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use App\Models\Request;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Request::create([
            'request_code' => 'REQ-1',
            'description' => 'Laptop overheating',
            'date_requested' => fake()->date(),
            'requested_by' => 2,
            'asset_id' => 2,
            'type' => RequestTypes::SERVICE,
            'service_type' => ServiceTypes::REPAIR
        ]);

        Request::create([
            'request_code' => 'REQ-2',
            'description' => 'Need new wireless mouse for workstation',
            'date_requested' => fake()->date(),
            'requested_by' => 2,
            'asset_name' => 'Logitech MX Master 3',
            'category_id' => 1,
            'sub_category_id' => 3,
            'type' => RequestTypes::REQUISITION,
        ]);

        Request::create([
            'request_code' => 'REQ-3',
            'description' => 'Old PC beyond repair, recommend disposal',
            'date_requested' => fake()->date(),
            'requested_by' => 2,
            'asset_id' => 2,
            'type' => RequestTypes::DISPOSAL,
        ]);

        Request::create([
            'request_code' => 'REQ-4',
            'description' => 'Old Table',
            'date_requested' => fake()->date(),
            'requested_by' => 2,
            'asset_id' => 3,
            'type' => RequestTypes::DISPOSAL,
            'status' => RequestStatus::APPROVED
        ]);
    }
}
