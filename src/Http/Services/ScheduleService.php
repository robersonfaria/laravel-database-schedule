<?php


namespace RobersonFaria\DatabaseSchedule\Http\Services;

class ScheduleService
{
    private $model;

    public function __construct()
    {
        $this->model = app(config('database-schedule.model'));
    }

    public function getActives()
    {
        if (config('database-schedule.cache.enabled')) {
            return $this->getFromCache();
        }
        return $this->model->active()->get();
    }

    public function clearCache()
    {
        $store = config('database-schedule.cache.store');
        $key = config('database-schedule.cache.key');

        cache()->store($store)->forget($key);
    }

    private function getFromCache()
    {
        $store = config('database-schedule.cache.store');
        $key = config('database-schedule.cache.key');

        return cache()->store($store)->rememberForever($key, function () {
            return $this->model->active()->get();
        });
    }
}
