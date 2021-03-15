<?php

namespace RobersonFaria\DatabaseSchedule\Observer;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;

class ScheduleObserver
{

    public function created(ScheduleService $scheduleService)
    {
        $scheduleService->clearCache();
    }

    public function updated(ScheduleService $scheduleService)
    {
        $scheduleService->clearCache();
    }

    public function deleted(ScheduleService $scheduleService)
    {
        $scheduleService->clearCache();
    }

    public function saved(ScheduleService $scheduleService)
    {
        $scheduleService->clearCache();
    }

}
