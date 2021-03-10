<?php


namespace RobersonFaria\DatabaseSchedule\Http\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ScheduleService
{
    private $model;

    public function __construct()
    {
        $this->model = app(Config::get('database-schedule.model'));
    }

    public function getActives()
    {
        if (Config::get('database-schedule.cache.enabled')) {
            return $this->getFromCache();
        }
        return $this->model->active()->get();
    }

    public function clearCache()
    {
        $store = Config::get('database-schedule.cache.store', 'file');
        $key = Config::get('database-schedule.cache.key', 'database_schedule');

        Cache::store($store)->forget($key);
    }

    private function getFromCache()
    {
        $store = Config::get('database-schedule.cache.store', 'file');
        $key = Config::get('database-schedule.cache.key', 'database_schedule');

        return Cache::store($store)->rememberForever($key, function () {
            return $this->model->active()->get();
        });
    }
}