<?php
namespace RobersonFaria\DatabaseSchedule\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use RobersonFaria\DatabaseSchedule\Models\Schedule;
use RobersonFaria\DatabaseSchedule\Tests\TestCase;

class ScheduleCommandTest extends TestCase
{

    public function testInspireTestCommand()
    {
        $task = factory(Schedule::class)
            ->create([
                'command' => 'inspire'
            ]);

        $this->artisan('schedule:run')->execute();

        $this->assertDatabaseHas(
            'schedule_histories',
            [
                'schedule_id' => $task->id,
                'command' => $task->command,
            ]
        );
    }

    public function testCommandWithRequiredArgument()
    {
        $task = factory(Schedule::class)
            ->create([
                'command' => 'phpunit:test',
                'params' => [
                    'argument' => [
                        'value' => 'this is a argument',
                        'type' => 'string'
                    ]
                ]
            ]);
        /** @var \Illuminate\Console\Scheduling\Schedule $schedule */
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);

        $events = collect($schedule->events())->filter(function (\Illuminate\Console\Scheduling\Event $event) use ($task) {
            return stripos($event->command, $task->command);
        });

        if ($events->count() == 0) {
            $this->fail('No events found');
        }

        $events->each(function (\Illuminate\Console\Scheduling\Event $event) use ($task) {
            // This example is for hourly commands.
            $this->assertEquals($task->expression, $event->expression);
            $this->assertStringEndsWith(
                "phpunit:test 'this is a argument'",
                $event->command
            );
        });
    }

    public function testCommandWithOptionalArgument()
    {
        $task = factory(Schedule::class)
            ->create([
                'command' => 'phpunit:test',
                'params' => [
                    'argument' => [
                        'value' => 'this is a argument',
                        'type' => 'string'
                    ],
                    'optionalArgument' => [
                        'value' => 'this is a optional argument',
                        'type' => 'string'
                    ]
                ]
            ]);
        /** @var \Illuminate\Console\Scheduling\Schedule $schedule */
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);

        $events = collect(
            $schedule->events())->filter(
                function (\Illuminate\Console\Scheduling\Event $event) use ($task) {
            return stripos($event->command, $task->command);
        });

        if ($events->count() == 0) {
            $this->fail('No events found');
        }

        $events->each(function (\Illuminate\Console\Scheduling\Event $event) use ($task) {
            // This example is for hourly commands.
            $this->assertEquals($task->expression, $event->expression);
            $this->assertStringEndsWith(
                "phpunit:test 'this is a argument' 'this is a optional argument'",
                $event->command
            );
        });
    }
}
