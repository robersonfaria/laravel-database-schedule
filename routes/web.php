<?php

use Illuminate\Support\Facades\Route;

Route::post('/{schedule}/status/{status}', 'ScheduleController@status');

Route::get('/', 'ScheduleController@index');
Route::post('/', 'ScheduleController@store');
Route::get('/create', 'ScheduleController@create');
Route::put('/{schedule}', 'ScheduleController@update');
Route::get('/{schedule}', 'ScheduleController@show');
Route::delete('/{schedule}', 'ScheduleController@destroy');
Route::get('/{schedule}/edit', 'ScheduleController@edit');