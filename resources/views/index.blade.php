@extends('schedule::layout.master')

@section('content')
    <div class="container-fluid">
        @include('schedule::messages')
        <div class="card">
            <div class="card-header">{{ trans('schedule::schedule.titles.list') }}
                <small><code>
                        {{ trans('schedule::schedule.messages.timezone') }}{{ config('database-schedule.timezone') }}
                    </code></small>
            </div>
            <div class="card-body table-responsive"
                 x-data="{
                 messageTemplate:'{{ trans('schedule::schedule.messages.delete_cronjob_confirm') }}',
                 message: '',
                 routeTemplate:'{{ route(config('database-schedule.route.name', 'database-schedule') . '.destroy', ['schedule' => '#ID#']) }}',
                 route: ''
            }">
                @include('schedule::delete-modal')

                @if ($schedules->isEmpty())
                    <p> {{ trans('schedule::schedule.messages.no-records-found') }} </p>
                @else
                    <table class="table table-bordered table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            {{ Helpers::buildHeader() }}
                        </tr>
                        <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->command }}@if ($schedule->command == 'custom')
                                        : {{ $schedule->command_custom }} @endif</td>
                                <td>
                                    @if(isset($schedule->params))
                                        @foreach($schedule->params as $param => $value)
                                            @if(isset($value['value']))
                                                {{ $param }}
                                                ={{ $value['value'] }}{{ $value['type'] === 'function' ? '()' : ''}}<br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if(isset($schedule->options))
                                        @foreach($schedule->options as $option => $value)
                                            @if(!is_array($value) || isset($value['value']))
                                                @if(is_array($value))
                                                    --{{ $option }}
                                                    ={{ $value['value'] }}{{ $value['type'] === 'function' ? '()' : ''}}
                                                @else
                                                    --{{ $option }}
                                                @endif
                                                <br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">{{ $schedule->expression }}</td>
                                @if(config('database-schedule.enable_groups', false))
                                    <td class="text-center">{{ $schedule->groups }}</td>
                                @endif
                                <td class="text-center">{{ $schedule->created_at }}</td>
                                <td class="text-center">{{ $schedule->created_at == $schedule->updated_at ? trans('schedule::schedule.fields.never') : $schedule->updated_at }}</td>
                                <td class="text-center {{ $schedule->status ? 'text-success' : 'text-secondary' }}">
                                    {{ $schedule->status ? trans('schedule::schedule.status.active') : trans('schedule::schedule.status.inactive') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@show', $schedule) }}"
                                       class="btn btn-sm btn-info">
                                        <i title="{{ trans('schedule::schedule.buttons.history') }}"
                                           class="bi bi-journal"> </i>
                                    </a>
                                    <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@edit', $schedule) }}"
                                       class="btn btn-sm btn-primary">
                                        <i title="{{ trans('schedule::schedule.buttons.edit') }}"
                                           class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route(config('database-schedule.route.name', 'database-schedule') . '.status', ['schedule' => $schedule->id, 'status' => $schedule->status ? 0 : 1]) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-{{ $schedule->status ? 'secondary' : 'success' }} btn-sm">
                                            <i title="{{ trans('schedule::schedule.buttons.' . ($schedule->status ? 'inactivate' : 'activate')) }}"
                                               class="bi {{ ($schedule->status ? 'bi-pause' : 'bi-play') }}"></i>
                                        </button>
                                    </form>
                                    <button
                                            x-on:click="message=messageTemplate.replace(':cronjob', '{{ $schedule->command }}'); route=routeTemplate.replace('#ID#', {{ $schedule->id }})"
                                            type="button" class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#delete-modal">
                                        <i title="{{ trans('schedule::schedule.buttons.delete') }}" class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                <div class='d-flex'>
                    <div class='mx-auto'>
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@create') }}"
                   class="btn btn-primary">
                    {{ trans('schedule::schedule.buttons.create') }}
                </a>
            </div>
        </div>
    </div>
@endsection
