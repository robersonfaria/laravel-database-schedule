<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;
use RobersonFaria\DatabaseSchedule\Models\ScheduleHistory;

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

            $command = $schedule->command;

            $options = $schedule->mapOptions();

            /**
             * @var Event
             */
            $event = $this
                ->command($command . $options, $schedule->mapArguments() ?? [])
                ->name(md5($command . $options . json_encode($schedule->mapArguments() ?? [])))
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
                $event->emailOutputTo($schedule->email_output);

                if ($schedule->sendmail_error) {
                    $event->emailOutputOnFailure($schedule->email_output);
                }
            }

            if (!empty($schedule->on_one_server)) {
                $event->onOneServer();
            }

            $event->after(function () use ($schedule, $event) {
                $schedule->histories()->create([
                    'command' => $schedule->command,
                    'params' => $schedule->params,
                    'options' => $schedule->options,
                    'output' => file_get_contents($event->output)
                ]);
            });
            unset($event);
        }

        return parent::dueEvents($app);
    }
}