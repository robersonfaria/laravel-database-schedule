@extends('schedule::layout.master')

@section('content')
    <div class="container">
        @include('schedule::messages')
        <form action="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">{{ trans('schedule::schedule.titles.create') }}</div>
                <div class="card-body">
                    @include('schedule::form')
                </div>
                <div class="card-footer text-right">
                    <div class="row">
                        <div class="col-6 text-left">
                            <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index') }}" class="btn btn-secondary">{{ trans('schedule::schedule.buttons.back') }}</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success">{{ trans('schedule::schedule.buttons.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection