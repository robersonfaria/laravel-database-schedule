<?php

namespace RobersonFaria\DatabaseSchedule\Console\Scheduling;

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

            $event = $this->command($schedule->command, [$schedule->params] ?? [])->cron($schedule->expression);

            if ($schedule->even_in_maintenance_mode) {
                $event->evenInMaintenanceMode();
            }

            if ($schedule->without_overlapping) {
                $event->withoutOverlapping();
            }

            if(!empty($schedule->webhook_before)) {
                $event->pingBefore($schedule->webhook_before);
            }

            if(!empty($schedule->webhook_after)) {
                $event->thenPing($schedule->webhook_after);
            }

            if(!empty($schedule->email_output)) {
                $event->emailOutputTo($schedule->email_output);

                if($schedule->sendmail_error) {
                    $event->emailOutputOnFailure($schedule->email_output);
                }
            }

            if(!empty($schedule->on_one_server)) {
                $event->onOneServer();
            }
        }

        return parent::dueEvents($app);
    }
}