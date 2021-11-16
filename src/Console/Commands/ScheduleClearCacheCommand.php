<?php

namespace RobersonFaria\DatabaseSchedule\Console\Commands;

use Illuminate\Console\Command;
use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;

class ScheduleClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the cache of the scheduler.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new ScheduleService())->clearCache();
        $this->info('Scheduling cache cleared.');
        return 0;
    }
}
