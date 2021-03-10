@extends('schedule::layout.master')

@section('content')
    <div class="container">
        @include('schedule::messages')
        <div class="card">
            <div class="card-header">{{ trans('schedule::schedule.titles.show') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-2">{{ trans('schedule::schedule.fields.command') }}:</div>
                    <div class="col-10">{{ $schedule->command }}</div>

                    <div class="col-2">{{ trans('schedule::schedule.fields.params') }}:</div>
                    <div class="col-10">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Param</th>
                                <th>Value</th>
                            </tr>
                        @foreach($schedule->params as $param => $value)
                            <tr>
                                <td>{{ $param }}</td>
                                <td>{{ $value['value'] }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                    <div class="col-2">{{ trans('schedule::schedule.fields.expression') }}:</div>
                    <div class="col-10">{{ $schedule->expression }}</div>
                    <div class="col-12">
                        <table class="table table-bordered table-striped table-sm table-hover">
                            <thead>
                            <tr class="d-flex">
                                <th class="col-2">{{ trans('schedule::schedule.fields.command') }}</th>
                                <th class="col-8">{{ trans('schedule::schedule.fields.params') }}</th>
                                <th class="col-2">{{ trans('schedule::schedule.fields.expression') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($schedule->histories as $history)
                                <tr class="d-flex">
                                    <td class="col-2">{{ $history->command }}</td>
                                    <td class="col-8">
                                        @foreach($history->params as $param => $value)
                                            {{ $param }}: {{ $value['value'] }}<br>
                                        @endforeach
                                    </td>
                                    <td class="col-2">{{ $history->created_at }}</td>
                                </tr>
                                <tr class="d-flex">
                                    <td colspan="3" class="col-12">
                                        <pre style="overflow: scroll;white-space: pre-wrap; max-width: 100%; height: 300px">
                                            {{ $history->output }}
                                        </pre>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="row">
                    <div class="col-6 text-left">
                        <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index') }}" class="btn btn-secondary">{{ trans('schedule::schedule.buttons.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
