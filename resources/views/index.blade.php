@extends('schedule::layout.master')

@section('content')
    <div class="container">
        @include('schedule::messages')
        <div class="card">
            <div class="card-header">{{ trans('schedule::schedule.titles.list') }}
                <small><code>
                    {{ trans('schedule::schedule.messages.timezone') }}{{ config('database-schedule.timezone') }}
                </code></small>
            </div>
            <div class="card-body"
                 x-data="{
                 messageTemplate:'{{ trans('schedule::schedule.messages.delete_cronjob_confirm') }}',
                 message: '',
                 routeTemplate:'{{ route(config('database-schedule.route.name', 'database-schedule') . '.destroy', ['schedule' => 0]) }}',
                 route: ''
            }">
                @include('schedule::delete-modal')
                <table class="table table-bordered table-striped table-sm table-hover">
                    <thead>
                    <tr>
                        <th class="text-center"><a href="{{ $route }}?orderBy=command">{!! Helpers::highlight('command', trans('schedule::schedule.fields.command')) !!}</a></th>
                        <th class="text-center"> {{ trans('schedule::schedule.fields.arguments') }}</th>
                        <th class="text-center"> {{ trans('schedule::schedule.fields.options') }}</th>
                        <th class="text-center text-nowrap"><a href="{{ $route }}?orderBy=expression">{!! Helpers::highlight('expression', trans('schedule::schedule.fields.expression')) !!}</a></th>
                        <th class="text-center"><a href="{{ $route }}?orderBy=status">{!! Helpers::highlight('status', trans('schedule::schedule.fields.status')) !!}</a></th>
                        <th class="text-center"><a href="{{ $route }}?orderBy=created_at">{!! Helpers::highlight('created_at', trans('schedule::schedule.fields.created_at')) !!}</a></th>
                        <th class="text-center">{{ trans('schedule::schedule.fields.updated_at') }}</a></th>
                        <th class="text-center" width="270">{{ trans('schedule::schedule.fields.actions') }}</th>
                    </tr>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->command }}@if ($schedule->command == 'custom'): {{ $schedule->command_custom }} @endif</td>
                            <td>
                                @if(isset($schedule->params))
                                    @foreach($schedule->params as $param => $value)
                                        @if(isset($value['value']))
                                            {{ $param }}={{ $value['value'] }}{{ $value['type'] === 'function' ? '()' : ''}}<br>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(isset($schedule->options))
                                    @foreach($schedule->options as $option => $value)
                                        @if(!is_array($value) || isset($value['value']))
                                            @if(is_array($value))
                                                --{{ $option }}={{ $value['value'] }}{{ $value['type'] === 'function' ? '()' : ''}}
                                            @else
                                                --{{ $option }}
                                            @endif
                                            <br>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{ $schedule->expression }}</td>
                            <td class="text-center {{ $schedule->status ? 'text-success' : 'text-secondary' }}">
                                {{ $schedule->status ? trans('schedule::schedule.status.active') : trans('schedule::schedule.status.inactive') }}
                            </td>
                            <td class="text-center">{{ $schedule->created_at }}</td>
                            <td class="text-center">{{ $schedule->created_at == $schedule->updated_at ? trans('schedule::schedule.fields.never') : $schedule->updated_at }}</td>
                            <td class="text-center">
                                <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@show', $schedule) }}"
                                   class="btn btn-sm btn-info">
                                    {{ trans('schedule::schedule.buttons.history') }}
                                </a>
                                <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@edit', $schedule) }}"
                                   class="btn btn-sm btn-primary">
                                    {{ trans('schedule::schedule.buttons.edit') }}
                                </a>
                                <form action="{{ route(config('database-schedule.route.name', 'database-schedule') . '.status', ['schedule' => $schedule->id, 'status' => $schedule->status ? 0 : 1]) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $schedule->status ? 'secondary' : 'success' }} btn-sm">
                                        {{ trans('schedule::schedule.buttons.' . ($schedule->status ? 'inactivate' : 'activate')) }}
                                    </button>
                                </form>
                                    <button
                                        x-on:click="message=messageTemplate.replace(':cronjob', '{{ $schedule->command }}'); route=routeTemplate.replace('0', {{ $schedule->id }})"
                                        type="button" class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#delete-modal">
                                        {{ trans('schedule::schedule.buttons.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                {{ trans('schedule::schedule.messages.no-records-found') }}
                            </td>
                        </tr>
                    @endforelse
                    </thead>
                </table>
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
