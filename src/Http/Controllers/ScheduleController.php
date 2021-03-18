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
        $schedules = Schedule::paginate(10);

        return view('schedule::index')
            ->with(compact('schedules'));
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
            Schedule::create($request->all());

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
