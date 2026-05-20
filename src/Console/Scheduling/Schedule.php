<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;
use \Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use Illuminate\Support\Facades\Log;
use Config;

class Schedule
{
    /**
     * @var BaseSchedule
     */
    private $schedule;

    private $tasks;

    public function __construct(ScheduleService $scheduleService, BaseSchedule $schedule)
    {
        $this->tasks = $scheduleService->getActives();
        $this->schedule = $schedule;
    }

    public function execute()
    {
        foreach ($this->tasks as $task) {
            $this->dispatch($task);
        }
    }

    /**
     * @throws \Exception
     */
    private function dispatch($task)
    {
        $model = config('database-schedule.model');
        if ($task instanceof $model) {
            // @var Event $event
            if ($task->command === 'custom') {
                $command = $task->command_custom;
                $event = $this->schedule->exec($command);
            } else {
                $command = $task->command;
                $event = $this->schedule->command(
                    $command,
                    array_values($task->getArguments()) + $task->getOptions()
                );
            }
            //Setting user timezone
            if($task->timezone != null){
                $timezone = $task->timezone;    
            } else {
                $timezone = config('database-schedule.timezone');    
            }
            if($timezone == null){
                $event->cron($task->expression);
            } else {
                $event->cron($task->expression)->timezone($timezone);
            }

            //ensure output is being captured to write history
            $event->storeOutput();

            if ($task->environments) {
                $event->environments(explode(',', $task->environments));
            }

            if ($task->even_in_maintenance_mode) {
                $event->evenInMaintenanceMode();
            }

            if ($task->without_overlapping) {
                $event->withoutOverlapping();
            }

            if ($task->run_in_background) {
                $event->runInBackground();
            }

            if (!empty($task->webhook_before)) {
                $event->pingBefore($task->webhook_before);
            }

            if (!empty($task->webhook_after)) {
                $event->thenPing($task->webhook_after);
            }

            if (!empty($task->email_output)) {
                if ($task->sendmail_success) {
                    $event->emailOutputTo($task->email_output);
                }

                if ($task->sendmail_error) {
                    $event->emailOutputOnFailure($task->email_output);
                }
            }

            if (!empty($task->on_one_server)) {
                $event->onOneServer();
            }

            $event->onSuccess(
                function () use ($task, $event, $command) {
                    $this->createLogFile($task, $event);
                    if ($task->log_success) {
                        $this->createHistoryEntry($task, $event, $command);
                    }
                }
            );

            $event->onFailure(
                function () use ($task, $event, $command) {
                    $this->createLogFile($task, $event, 'critical');
                    if ($task->log_error) {
                        $this->createHistoryEntry($task, $event, $command);
                    }
                }
            );

            $event->after(function () use ($event) {
                unlink($event->output);
            });

            unset($event);
        } else {
            throw new \Exception('Task with invalid instance type');
        }
    }

    private function createLogFile($task, $event, $type = 'info')
    {
        if ($task->log_filename) {
            $logChannel = Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/' . $task->log_filename . '.log'),
            ]);
            Log::stack([$logChannel])->$type(file_get_contents($event->output));
        }
    }

    private function createHistoryEntry($task, $event, $command)
    {
        $task->histories()->create(
            [
                'command' => $command,
                'params' => $task->getArguments(),
                'options' => $task->getOptions(),
                'output' => file_get_contents($event->output)
            ]
        );
    }
}
