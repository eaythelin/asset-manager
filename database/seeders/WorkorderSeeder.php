<?php

namespace Database\Seeders;

use App\Enums\WorkorderType;
use App\Enums\RequestTypes;
use App\Enums\RequestStatus;
use App\Models\DisposalWorkorder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workorder;
use App\Models\Request as RequestModel;

class WorkorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theRequest = RequestModel::create([
            'request_code' => 'REQ-4',
            'description' => 'Old Table',
            'date_requested' => fake()->date(),
            'requested_by' => 2,
            'asset_id' => 3,
            'type' => RequestTypes::DISPOSAL,
            'status' => RequestStatus::APPROVED
        ]);

        $workorder = Workorder::create([
            "workorder_code" => "WO-DIS-1",
            "request_id" => $theRequest->id,
            "type" => WorkorderType::DISPOSAL,
            "start_date" => now(),
            "end_date" => now()->addDays(7)
        ]);

        DisposalWorkorder::create([
            "workorder_id" => $workorder->id,
            "asset_id" => $theRequest->asset_id
        ]);
    }
}
