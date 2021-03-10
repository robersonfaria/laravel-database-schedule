<?php

use Illuminate\Support\Facades\Route;

Route::post('/{schedule}/status/{status}', 'ScheduleController@status');
Route::resource('/', 'ScheduleController');
