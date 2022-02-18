<?php

namespace RobersonFaria\DatabaseSchedule\Tests\Unit;

use Mockery;
use RobersonFaria\DatabaseSchedule\Models\Schedule;
use RobersonFaria\DatabaseSchedule\Tests\TestCase;

class ScheduleCommandsTest extends TestCase
{
    private $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = $this->mock(\Illuminate\Console\Scheduling\Event::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('cron')
                ->once()
                ->with('* * * * *');

            $mock->shouldReceive('storeOutput')
                ->once();

            $mock->shouldReceive('onSuccess')
                ->once();

            $mock->shouldReceive('onFailure')
                ->once();

            $mock->shouldReceive('after')
                ->once();
        });
    }

    public function testRunInspireCommand()
    {
        factory(Schedule::class)
            ->create([
                'command' => 'inspire'
            ]);


        $this->mock(\Illuminate\Console\Scheduling\Schedule::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('command')
                ->once()
                ->with('inspire', [])
                ->andReturn($this->event);
        });

        $scheduleService = app(\RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule::class);
        $scheduleService->execute();
    }

    public function testRunInspireWithArguments()
    {
        factory(Schedule::class)
            ->create([
                'command' => 'inspire',
                'params' => [
                    "test" => ["value" => "1", "type" => "string"]
                ]
            ]);

        $this->mock(\Illuminate\Console\Scheduling\Schedule::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('command')
                ->once()
                ->with('inspire', ['1'])
                ->andReturn($this->event);
        });

        $scheduleService = app(\RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule::class);
        $scheduleService->execute();
    }

    public function testRunInspireWithOptionalArguments()
    {
        factory(Schedule::class)
            ->create([
                'command' => 'inspire',
                'params' => [
                    "test" => ["value" => null, "type" => "string"]
                ]
            ]);

        $this->mock(\Illuminate\Console\Scheduling\Schedule::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('command')
                ->once()
                ->with('inspire', [])
                ->andReturn($this->event);
        });

        $scheduleService = app(\RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule::class);
        $scheduleService->execute();
    }

    public function testRunInspireWithOptionsBoolean()
    {
        factory(Schedule::class)
            ->create([
                'command' => 'inspire',
                'options' => [
                    "argDisabledTrue" => "on"
                ]
            ]);

        $this->mock(\Illuminate\Console\Scheduling\Schedule::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('command')
                ->once()
                ->with('inspire', ['--argDisabledTrue'])
                ->andReturn($this->event);
        });

        $scheduleService = app(\RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule::class);
        $scheduleService->execute();
    }
}
