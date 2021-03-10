<?php

use Illuminate\Support\Facades\Route;

Route::namespace("\RobersonFaria\DatabaseSchedule\Http\Controllers")
    ->middleware('web')
    ->group(function () {
        Route::post(config('database-schedule.route.prefix') . '/{schedule}/status/{status}', 'ScheduleController@status');
        Route::resource(config('database-schedule.route.prefix'), 'ScheduleController')->except('show');
    });