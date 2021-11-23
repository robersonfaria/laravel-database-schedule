@extends('schedule::layout.master')

@section('content')
    <div class="container-fluid">
        @include('schedule::messages')
        <div class="card">
            <div class="card-header">{{ trans('schedule::schedule.titles.show') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 my-3">
                        <table class="table table-bordered table-striped table-sm table-hover">
                            <thead>
                                <tr class="d-flex">
                                    <th class="col-2">{{ trans('schedule::schedule.fields.command') }}</th>
                                    <th class="col-4">{{ trans('schedule::schedule.fields.arguments') }}</th>
                                    <th class="col-4">{{ trans('schedule::schedule.fields.options') }}</th>
                                    <th class="col-2">{{ trans('schedule::schedule.fields.expression') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($schedule->histories as $history)
                                <tr class="d-flex">
                                    <td class="col-2">{{ $history->command }}</td>
                                    <td class="col-4">
                                        @if(isset($history->params))
                                            @foreach($history->params as $param => $value)
                                                {{ $param }}={{$value}}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="col-4">
                                        @if(isset($history->options))
                                            @foreach($history->options as $param => $value)
                                                @if(is_integer($param))
                                                    {{ $value }}
                                                @else
                                                    {{ $param }}={{ $value }}
                                                @endif
                                                <br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="col-2">{{ $history->created_at }}</td>
                                </tr>
                                <tr class="d-flex">
                                    <td colspan="2" class="col-12">
                                        <pre style="overflow: scroll;white-space: pre-wrap; max-width: 100%; height: 200px">
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
