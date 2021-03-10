<?php

namespace RobersonFaria\DatabaseSchedule\Observer;

use RobersonFaria\DatabaseSchedule\Http\Services\ScheduleService;

class ScheduleObserver
{

    public function __construct(ScheduleService $scheduleService)
    {
        dd("asdf");
    }

}
