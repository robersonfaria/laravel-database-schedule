<?php

namespace RobersonFaria\DatabaseSchedule\Console\Commands;

use Illuminate\Console\Command;

class PhpUnitTestJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "phpunit:test {argument} {argumentWithDefault=Default value} {optionalArgument?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for testing the phpunit feature.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Argument required: ' . $this->argument('argument'));
        $this->info('Argument with default: ' . $this->argument('argumentWithDefault'));
        return 0;
    }
}
