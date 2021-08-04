<?php

use Illuminate\Support\Facades\Route;

Route::post('/{schedule}/status/{status}', 'ScheduleController@status')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.status');

Route::get('/', 'ScheduleController@index')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.index');
Route::post('/', 'ScheduleController@store')
    ->name(config('database-schedule.route.name', 'database-schedule') . '.store');
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
