<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;

class Schedule extends BaseSchedule
{
    protected $isScheduleAdded = false;

    public function dueEvents($app)
    {
        if ($this->isScheduleAdded) {
            return parent::dueEvents($app);
        }
        $scheduleService = app(ScheduleService::class);
        $schedules = $scheduleService->getActives();

        foreach ($schedules as $schedule) {
            // @var Event $event
            if ($schedule->command === 'custom') {
                $command = $schedule->command_custom;
                $commandName = $command;
                $event = $this->exec($command);
            } else {
                $command = $schedule->command . $schedule->mapOptions();
                $commandName = $schedule->command . $schedule->mapOptions() . " " . $this->argumentsToString($schedule->mapArguments() ?? []);
                $event = $this->command($command, array_values($schedule->mapArguments()) ?? []);
            }

            $event->name($commandName)
                ->cron($schedule->expression);

            if ($schedule->even_in_maintenance_mode) {
                $event->evenInMaintenanceMode();
            }

            if ($schedule->without_overlapping) {
                $event->withoutOverlapping();
            }

            if ($schedule->run_in_background) {
                $event->runInBackground();
            }

            if (!empty($schedule->webhook_before)) {
                $event->pingBefore($schedule->webhook_before);
            }

            if (!empty($schedule->webhook_after)) {
                $event->thenPing($schedule->webhook_after);
            }

            if (!empty($schedule->email_output)) {
                if ($schedule->sendmail_success) {
                    $event->emailOutputTo($schedule->email_output);
                }

                if ($schedule->sendmail_error) {
                    $event->emailOutputOnFailure($schedule->email_output);
                }
            }

            if (!empty($schedule->on_one_server)) {
                $event->onOneServer();
            }

            $event->onSuccess(
                function () use ($schedule, $event, $command) {
                    $schedule->histories()->create(
                        [
                            'command' => $command,
                            'params' => $schedule->params,
                            'options' => $schedule->options,
                            'output' => file_get_contents($event->output)
                        ]
                    );
                }
            );
            $event->onFailure(
                function () use ($schedule, $event, $command) {
                    $schedule->histories()->create(
                        [
                            'command' => $command,
                            'params' => $schedule->params,
                            'options' => $schedule->options,
                            'output' => file_get_contents($event->output)
                        ]
                    );
                }
            );
            unset($event);
        }

        return parent::dueEvents($app);
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
