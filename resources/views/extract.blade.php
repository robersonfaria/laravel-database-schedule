@extends('schedule::layout.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ trans('schedule::schedule.titles.extract') }}</span>
                        <a href="{{ action('\RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController@index') }}"
                           class="btn btn-secondary btn-sm">
                            {{ trans('schedule::schedule.buttons.back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        @include('schedule::messages')
                        
                        @if(count($extractedSchedules) > 0)
                            <form method="POST" action="{{ route(config('database-schedule.route.name', 'database-schedule') . '.import') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                        {{ trans('schedule::schedule.buttons.select_all') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNone()">
                                        {{ trans('schedule::schedule.buttons.select_none') }}
                                    </button>
                                    <button type="submit" class="btn btn-success btn-sm ml-2">
                                        {{ trans('schedule::schedule.buttons.import_selected') }}
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">
                                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()">
                                                </th>
                                                <th>{{ trans('schedule::schedule.fields.command') }}</th>
                                                <th>{{ trans('schedule::schedule.fields.expression') }}</th>
                                                <th>{{ trans('schedule::schedule.fields.environments') }}</th>
                                                <th>{{ trans('schedule::schedule.fields.settings') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($extractedSchedules as $index => $schedule)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox"
                                                               name="schedules[]"
                                                               value="{{ json_encode($schedule) }}"
                                                               class="schedule-checkbox">
                                                    </td>
                                                    <td>
                                                        <strong>{{ $schedule['command'] }}</strong>
                                                        @if(!empty($schedule['params']))
                                                            <br>
                                                            @foreach($schedule['params'] as $key => $param)
                                                                <small class="text-muted">{{ $key }}: {{ $param }}</small>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td><code>{{ $schedule['expression'] }}</code></td>
                                                    <td>
                                                        @if($schedule['environments'])
                                                            <span class="badge badge-warning">{{ $schedule['environments'] }}</span>
                                                        @else
                                                            <span class="text-muted">{{ trans('schedule::schedule.messages.all-environments') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($schedule['without_overlapping'])
                                                            <span class="badge badge-secondary">{{ trans('schedule::schedule.messages.no-overlap') }}</span>
                                                        @endif
                                                        @if($schedule['on_one_server'])
                                                            <span class="badge badge-info">{{ trans('schedule::schedule.messages.one-server') }}</span>
                                                        @endif
                                                        @if($schedule['status'])
                                                            <span class="badge badge-success">{{ trans('schedule::schedule.status.active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ trans('schedule::schedule.status.inactive') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                {{ trans('schedule::schedule.messages.no-schedules-found') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('.schedule-checkbox').forEach(cb => cb.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
        }

        function selectNone() {
            document.querySelectorAll('.schedule-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
        }

        function toggleAll() {
            const selectAll = document.getElementById('selectAllCheckbox').checked;
            document.querySelectorAll('.schedule-checkbox').forEach(cb => cb.checked = selectAll);
        }
    </script>
@endsection
