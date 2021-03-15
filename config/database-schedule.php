<?php

use \Illuminate\Support\Str;
use \RobersonFaria\DatabaseSchedule\Models\Schedule;

return [
    /**
     *  Table and Model used for schedule list
     */
    'table' => [
        'schedules' => 'schedules',
        'schedule_histories' => 'schedule_histories'
    ],
    'model' => Schedule::class,
    'timezone' => env('SCHEDULE_TIMEZONE', config('app.timezone')),
    'middleware' => 'web',

    /**
     * Cache settings
     */
    'cache' => [
        'store' => env('SCHEDULE_CACHE_DRIVER', 'file'),
        'key' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_schedule_'),
        'enabled' => env('SCHEDULE_CACHE_ENABLE', !config('app.debug')),
    ],

    /**
     * Route settings
     */
    'route' => [
        'prefix' => 'schedule'
    ],

    /**
     * Commands settings
     */
    'commands' => [

        /**
         * By default, all commands possible to be used with "php artisan" will be shown, this parameter excludes from
         * the list commands that you do not want to show for the schedule.
         */
        'exclude' => [ //regex
            'help',
            'list',
            'test',
            'down',
            'up',
            'env',
            'serve',
            'tinker',
            'clear-compiled',
            'key:generate',
            'package:discover',
            'storage:link',
            'notifications:table',
            'session:table',
            'stub:publish',
            'vendor:publish',
            'route:*',
            'event:*',
            'migrate:*',
            'cache:*',
            'auth:*',
            'config:*',
            'db:*',
            'optimize*',
            'make:*',
            'queue:*',
            'schedule:*',
            'view:*'
        ]
    ],

    'tool-help-cron-expression' => [
        'enable' => true,
        'url' => 'https://crontab.cronhub.io/'
    ]
];
