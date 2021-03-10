<?php

namespace RobersonFaria\DatabaseSchedule;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DatabaseScheduleApplicationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->authorization();
    }

    protected function authorization()
    {
        $this->gate();
    }

    protected function gate()
    {
        Gate::define('viewDatabaseSchedule', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}