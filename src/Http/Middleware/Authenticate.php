<?php

namespace RobersonFaria\DatabaseSchedule\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class Authenticate
{
    public function handle($request, $next)
    {
        if(config('database-schedule.restricted_access')) {
            $guard = config('database-schedule.guard', 'web');
            if (Gate::check('viewDatabaseSchedule', [$request->user($guard)])) {
                return $next($request);
            }
            abort(403);
        }
        return $next($request);
    }
}
