<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;

class Schedule
{
    public function __construct(ScheduleService $scheduleService, $schedule)
    {
        $tasks = $scheduleService->getActives();

        foreach ($tasks as $task) {
            // @var Event $event
            if ($task->command === 'custom') {
                $command = $task->command_custom;
                $event = $schedule->exec($command);
            } else {
                $command = $task->command . $task->mapOptions();
                $event = $schedule->command(
                    $command,
                    array_values($task->mapArguments()) ?? []
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

            $event->onSuccess(
                function () use ($task, $event, $command) {
                    $task->histories()->create(
                        [
                            'command' => $command,
                            'params' => $task->params,
                            'options' => $task->options,
                            'output' => file_get_contents($event->output)
                        ]
                    );
                }
            );
            $event->onFailure(
                function () use ($task, $event, $command) {
                    $task->histories()->create(
                        [
                            'command' => $command,
                            'params' => $task->params,
                            'options' => $task->options,
                            'output' => file_get_contents($event->output)
                        ]
                    );
                }
            );
            unset($event);
        }
    }

    public function argumentsToString($array)
    {
        $str = '';
        foreach ($array as $key => $value) {
            $str .= " {$key}={$value}";
        }
        return $str;
    }
}
