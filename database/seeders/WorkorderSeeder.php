<?php

namespace Database\Seeders;

use App\Enums\WorkorderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workorder;

class WorkorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workorder::create([
            "workorder_code" => "WO-DIS-1",
            "request_id" => 4,
            "type" => WorkorderType::DISPOSAL,
            "start_date" => now(),
            "end_date" => now()->addDays(7)
        ]);
    }
}
