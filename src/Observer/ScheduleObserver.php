<?php

namespace RobersonFaria\DatabaseSchedule\Observer;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;
use RobersonFaria\DatabaseSchedule\Models\Schedule;

class ScheduleObserver
{

    public function created()
    {
        $scheduleService = app(ScheduleService::class);
        $scheduleService->clearCache();
    }

    public function updated(Schedule $schedule)
    {
        $scheduleService = app(ScheduleService::class);
        $scheduleService->clearCache();
    }

    public function deleted(Schedule $schedule)
    {
        $scheduleService = app(ScheduleService::class);
        $scheduleService->clearCache();
    }

    public function saved(Schedule $schedule)
    {
        $scheduleService = app(ScheduleService::class);
        $scheduleService->clearCache();
    }

}
