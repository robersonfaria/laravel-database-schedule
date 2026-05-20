<?php

namespace RobersonFaria\DatabaseSchedule\Http\Services;

class ImportService
{
    public function importSchedules(array $selectedSchedules): void
    {
        $modelClass = config('database-schedule.model');

        foreach ($selectedSchedules as $scheduleData) {
            $data = json_decode($scheduleData, true, 512, JSON_THROW_ON_ERROR);

            $modelClass::updateOrCreate(
                [
                    'command' => $data['command'],
                    'expression' => $data['expression'],
                ],
                $data
            );
        }
    }
}
