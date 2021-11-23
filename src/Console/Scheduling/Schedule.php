<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;
use \Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use Illuminate\Support\Facades\Log;

class Schedule
{
    /**
     * @var BaseSchedule
     */
    private $schedule;

    public function __construct(ScheduleService $scheduleService, BaseSchedule $schedule)
    {
        $tasks = $scheduleService->getActives();

        foreach ($tasks as $task) {
           $this->dispatch($task);
        }
        $this->schedule = $schedule;
    }

    /**
     * @param $task
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
                    $task->getArguments() + $task->getOptions()
                );
            }

            $event->cron($task->expression);

            //ensure output is being captured to write history
            $event->storeOutput();

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

            $logChannel = $channel = Log::build([
                'driver' => 'single',
                'path' => $task->log_filename ? storage_path('logs/' . $task->log_filename . '.log') : null,
            ]);

            $event->onSuccess(
                function () use ($task, $event, $command, $logChannel) {
                    Log::stack([$logChannel])->info(file_get_contents($event->output));
                    if ($task->log_success) {
                        $this->createHistoryEntry($task, $event, $command);
                    }
                }
            );
            $event->onFailure(
                function () use ($task, $event, $command, $logChannel) {
                    Log::stack([$logChannel])->critical(file_get_contents($event->output));
                    if ($task->log_error) {
                        $this->createHistoryEntry($task, $event, $command);
                    }
                }
            );
            unset($event);
        } else {
            throw new \Exception('Task with invalid instance type');
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
