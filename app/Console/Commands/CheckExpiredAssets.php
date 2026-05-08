<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class CheckExpiredAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-assets';

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
        $assets = Asset::with(['category'])->get();
        $assets->each(fn($asset) => $asset->computed_status); 
        $this->info('Done checking expired assets!');
    }
}
