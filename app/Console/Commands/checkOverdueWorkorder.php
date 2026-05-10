<?php

namespace App\Console\Commands;

use App\Models\Workorder;
use Illuminate\Console\Command;

class checkOverdueWorkorder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-workorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workorders = Workorder::with('request')->where('is_direct', false)->get();
        $workorders->each(fn($workorders) => $workorders->check_status);
        $this->info('Done checking for overdue workorders!');
    }
}
