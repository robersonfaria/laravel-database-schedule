<?php

namespace RobersonFaria\DatabaseSchedule;

use Cron\CronExpression;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use RobersonFaria\DatabaseSchedule\Observer\ScheduleObserver;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule;

class DatabaseSchedulingServiceProvider extends DatabaseScheduleApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerRoutes();

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'schedule');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'schedule');

        Validator::extend('cron', function ($attribute, $value, $parameters, $validator) {
            return CronExpression::isValidExpression($value);
        });

        $this->publishes([
            __DIR__ . '/../config/database-schedule.php' => config_path('database-schedule.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/lang/' => resource_path('lang/vendor/schedule'),
        ], 'translates');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/schedule'),
        ], 'views');

        $config = $this->app['config'];

        if ($config->get('database-schedule.cache.enabled')) {
            $model = $config->get('database-schedule.model');
            $model::observe(ScheduleObserver::class);
        }

        $this->app->extend(BaseSchedule::class, function () use ($config) {
            return (new Schedule($this->scheduleTimezone($config)))
                ->useCache($this->scheduleCache());
        });
    }

    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('database-schedule.route.prefix'),
            'namespace' => 'RobersonFaria\DatabaseSchedule\Http\Controllers',
            'middleware' => config('database-schedule.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/database-schedule.php',
            'database-schedule'
        );
    }

    protected function scheduleTimezone($config)
    {
        return $config->get('schedule.timezone');
    }

    protected function scheduleCache()
    {
        return $_ENV[''] ?? null;
    }
}
