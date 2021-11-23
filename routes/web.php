<?php

use Illuminate\Support\Facades\Route;

Route::post('/{schedule}/status/{status}', 'ScheduleController@status')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.status');

Route::get('/', function () {
    return redirect()->route(config('database-schedule.route.name', 'database-schedule') . '.index');
});

Route::match(['get', 'post'], '/index', 'ScheduleController@index')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.index');

Route::post('/', 'ScheduleController@store')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.store');
Route::post('/filter', 'ScheduleController@filter')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.filter');
Route::post('/filter-reset', 'ScheduleController@filterReset')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.filter-reset');
Route::get('/create', 'ScheduleController@create')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.create');
Route::put('/{schedule}', 'ScheduleController@update')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.update');
Route::get('/{schedule}', 'ScheduleController@show')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.show');
Route::delete('/{schedule}', 'ScheduleController@destroy')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.destroy');
Route::get('/{schedule}/edit', 'ScheduleController@edit')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.edit');

// @link https://laracasts.com/discuss/channels/laravel/route-model-binding-with-soft-deleted-model
Route::post('/{thrashed_schedule}/restore', 'ScheduleController@restore')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.restore');
Route::bind('thrashed_schedule', function ($id) {
    $schedule = app(config('database-schedule.model'));
    return $schedule::onlyTrashed()->find($id);
});
