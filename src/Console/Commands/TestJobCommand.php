<?php

namespace RobersonFaria\DatabaseSchedule\Console\Commands;

use Illuminate\Console\Command;

class TestJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:test-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that display a friendly message that is intented to test a job.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Hello the test worked.');
        \Log::info('Hello the test worked.');
        return 0;
    }
}
