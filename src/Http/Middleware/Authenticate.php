<?php

namespace RobersonFaria\DatabaseSchedule\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class Authenticate
{
    public function handle($request, $next)
    {
        if(config('database-schedule.restricted_access')) {
            if (Gate::check('viewDatabaseSchedule', [$request->user()])) {
                return $next($request);
            }
            abort(403);
        }
        return $next($request);
    }
}
