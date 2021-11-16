<?php

namespace RobersonFaria\DatabaseSchedule\Http\Controllers;

use RobersonFaria\DatabaseSchedule\Http\Requests\ScheduleRequest;
use RobersonFaria\DatabaseSchedule\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedule.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $schedule = app(config('database-schedule.model'));
        $schedules = $schedule->paginate(config('database-schedule.per_page') ?? 10);

        $schedules = $schedule::query();

        if (request()->has('orderBy') && in_array(request()->get('orderBy'), ['command', 'status', 'expression', 'created_at'])) {
            $direction = 'ASC';
            if (strpos(url()->previous(), request()->get('orderBy')) !== false) {
                $direction = 'DESC';
            }
            $schedules->orderBy(request()->get('orderBy'), $direction);
        } else {
            // default ordering - youngest at the top
            $schedules->orderBy(
                config('database-schedule.default_ordering') ?? 'created_at',
                config('database-schedule.default_ordering_direction') ?? 'ASC',
            );
        }

        $schedules = $schedules->cursorPaginate(config('database-schedule.per_page') ?? 10);
        $route = route(config('database-schedule.route.name', 'database-schedule') . '.index');

        return view('schedule::index')
            ->with(compact('schedules', 'route'));
    }

    /**
     * Show the form for creating a new schedule.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('schedule::create');
    }

    /**
     * Store a newly created schedule.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ScheduleRequest $request)
    {
        try {
            $schedule = app(config('database-schedule.model'));
            $schedule->create($request->all());

            return redirect()
                ->action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index')
                ->with('success', trans('schedule::schedule.messages.save-success'));
        } catch (\Exception $e) {
            report($e);
            return back()
                ->with('error', trans('schedule::schedule.messages.save-error'))
                ->withInput();
        }
    }

    /**
     * Display the schedule.
     *
     * @param Schedule $schedule
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['histories' => function ($query) {
            $query->latest();
        }]);

        return view('schedule::show')
            ->with(compact('schedule'));
    }

    /**
     * Show the form for editing the schedule.
     *
     * @param Schedule $schedule
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Schedule $schedule)
    {
        return view('schedule::edit')
            ->with(compact('schedule'));
    }

    /**
     * Update the schedule
     *
     * @param ScheduleRequest $request
     * @param Schedule $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        try {
            $schedule->update($request->all());

            return redirect()
                ->action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index')
                ->with('success', trans('schedule::schedule.messages.save-success'));
        } catch (\Exception $e) {
            report($e);
            return back()
                ->with('error', trans('schedule::schedule.messages.save-error'))
                ->withInput();
        }
    }

    public function status(Schedule $schedule, bool $status)
    {
        try {
            $schedule->status = $status;
            $schedule->save();

            return redirect()
                ->action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index')
                ->with('success', trans('schedule::schedule.messages.save-success'));
        } catch (\Exception $e) {
            report($e);
            return back()
                ->with('error', trans('schedule::schedule.messages.save-error'))
                ->withInput();
        }
    }

    /**
     * Remove the schedule
     *
     * @param Schedule $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();

            return redirect()
                ->action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index')
                ->with('success', trans('schedule::schedule.messages.save-success'));
        } catch (\Exception $e) {
            report($e);
            return back()
                ->with('error', trans('schedule::schedule.messages.save-error'))
                ->withInput();
        }
    }
}
