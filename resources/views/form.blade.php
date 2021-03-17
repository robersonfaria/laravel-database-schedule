<div id="app-form">
    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.command') }}</label>
        @inject('commandService', 'RobersonFaria\DatabaseSchedule\Http\Services\CommandService')
        <select name="command"
                id="command"
                v-model="form.command"
                class="form-control @error('command') is-invalid @enderror"
                @if(isset($schedule) && $schedule->command) disabled @endif>
            <option value="">{{ trans('schedule::schedule.messages.select') }}</option>
            @foreach($commandService->get() as $command)
                <option value="{{ $command->name }}">
                    {{ $command->signature }} - {{ $command->description }}
                </option>
            @endforeach
            <option value="custom"> {{ trans('schedule::schedule.messages.custom') }} </option>
        </select>
        @error('command')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($schedule) && $schedule->command)
            <input type="hidden" name="command" value="{{ $schedule->command }}">
        @endif
    </div>

    <div id="parameters" class="ml-3" v-if="commands[form.command] !== undefined && commands[form.command].arguments.length > 0">
        <div class="row">
            <div class="col-12">
                <label>{{ trans('schedule::schedule.fields.arguments') }}:</label>
                <div id="div_params" class="ml-5 row" v-for="argument in commands[form.command].arguments" :key="argument.name">
                    <div class="col-8">
                        <label>@{{ argument.name }}</label>
                        <input type="text" class="form-control" :name="'params['+argument.name+'][value]'"
                               :id="argument.name"
                               :value="getArguments(argument.name)"
                               :required="argument.required">
                    </div>
                    <div class="col-4">
                        <label>{{ trans('schedule::schedule.fields.data-type') }}</label>
                        <select :name="'params['+argument.name+'][type]'" :value="getArgumentsType(argument.name)" class="form-control">
                            <option value="string">String</option>
                            <option value="function">Function</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <code>{{ trans('schedule::schedule.messages.attention-type-function') }}</code>
    </div>

    <div id="parameters" class="ml-3" v-if="commands[form.command] !== undefined && commands[form.command].options.length > 0">
        <div class="row">
            <div class="col-12">
                <label>{{ trans('schedule::schedule.fields.options') }}:</label>
                <div id="div_params" class="ml-5 row" v-for="option in commands[form.command].options" :key="option.name">
                    <div class="col-8">
                        <label>@{{ option.name }}</label>
                        <input type="text" class="form-control" :name="'options['+option.name+'][value]'"
                               :id="option.name"
                               :value="getOptions(option.name)"
                               :required="option.required">
                    </div>
                    <div class="col-4">
                        <label>{{ trans('schedule::schedule.fields.data-type') }}</label>
                        <select :name="'options['+option.name+'][type]'" :value="getOptionsType(option.name)" class="form-control">
                            <option value="string">String</option>
                            <option value="function">Function</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <code>{{ trans('schedule::schedule.messages.attention-type-function') }}</code>
    </div>

    <div v-if="form.command == 'custom'" class="form-group">
        <div class="row">
            <div class="col-12">
                <input
                   type="text"
                   placeholder="{{ trans('schedule::schedule.messages.custom-command-here')}}"
                   name="command_custom"
                   v-model="form.command_custom"
                   class="form-control @error('command_custom') is-invalid @enderror"
            />
            @error('command_custom')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.expression') }}</label>
        <input type="text" class="form-control @error('expression') is-invalid @enderror" name="expression"
               id="expression"
               value="{{ old('expression', $schedule->expression ?? '') }}">
        @error('expression')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(config('database-schedule.tool-help-cron-expression.enable'))
            <small id="expressiondHelpBlock" class="form-text text-muted">
                <a href="{{ config('database-schedule.tool-help-cron-expression.url') }}" target="_blank">
                    {{ trans("schedule::schedule.messages.help-cron-expression") }}
                </a>
            </small>
        @endif
    </div>

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.webhook_before') }}</label>
        <input type="text" class="form-control @error('webhook_before') is-invalid @enderror" name="webhook_before"
               id="webhook_before"
               value="{{ old('webhook_before', $schedule->webhook_before ?? '') }}">
        @error('webhook_before')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.webhook_after') }}</label>
        <input type="text" class="form-control @error('webhook_after') is-invalid @enderror" name="webhook_after"
               id="webhook_after"
               value="{{ old('webhook_after', $schedule->webhook_after ?? '') }}">
        @error('webhook_after')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.email_output') }}</label>
        <input type="email" class="form-control @error('email_output') is-invalid @enderror" name="email_output"
               id="email_output"
               value="{{ old('email_output', $schedule->email_output ?? '') }}">
        @error('email_output')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input @error('sendmail_error') is-invalid @enderror"
               name="sendmail_error"
               id="sendmail_error"
               value="1"
                {{ old('sendmail_error', $schedule->sendmail_error ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="sendmail_error">{{ trans('schedule::schedule.fields.sendmail_error') }}</label>
        @error('sendmail_error')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input @error('even_in_maintenance_mode') is-invalid @enderror"
               name="even_in_maintenance_mode"
               id="even_in_maintenance_mode"
               value="1"
                {{ old('even_in_maintenance_mode', $schedule->even_in_maintenance_mode ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="even_in_maintenance_mode">{{ trans('schedule::schedule.fields.even_in_maintenance_mode') }}</label>
        @error('even_in_maintenance_mode')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input @error('without_overlapping') is-invalid @enderror"
               name="without_overlapping"
               id="without_overlapping"
               value="1"
                {{ old('without_overlapping', $schedule->without_overlapping ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="without_overlapping">{{ trans('schedule::schedule.fields.without_overlapping') }}</label>
        @error('without_overlapping')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input @error('on_one_server') is-invalid @enderror"
               name="on_one_server"
               id="on_one_server"
               value="1"
                {{ old('on_one_server', $schedule->on_one_server ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="on_one_server">{{ trans('schedule::schedule.fields.on_one_server') }}</label>
        @error('on_one_server')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input @error('run_in_background') is-invalid @enderror"
               name="run_in_background"
               id="run_in_background"
               value="1"
                {{ old('run_in_background', $schedule->run_in_background ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="run_in_background">{{ trans('schedule::schedule.fields.run_in_background') }}</label>
        @error('run_in_background')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('js')
<script>
    var app = new Vue({
        el: '#app-form',
        data: {
            commands: @json($commandService->get()),
            requests: {
                arguments: @json(old('params') ?? $schedule->params ?? []),
                options: @json(old('options') ?? $schedule->options ?? [])
            },
            form: {
                command: '{{ old('command', $schedule->command ?? '') }}',
                'command_custom': '{{ old('command_custom', $schedule->command_custom ?? '') }}',
                params: []
            }
        },
        methods: {
            getArguments: function (field) {
                if(this.requests.arguments !== null && this.requests.arguments[field] !== undefined) {
                    return this.requests.arguments[field].value;
                }
                return '';
            },
            getArgumentsType: function (field) {
                if(this.requests.arguments !== null && this.requests.arguments[field] !== undefined) {
                    return this.requests.arguments[field].type;
                }
                return '';
            },
            getOptions: function (field) {
                if(this.requests.options !== null && this.requests.options[field] !== undefined) {
                    return this.requests.options[field].value;
                }
                return '';
            },
            getOptionsType: function (field) {
                if(this.requests.options !== null && this.requests.options[field] !== undefined) {
                    return this.requests.options[field].type;
                }
                return '';
            }
        }
    })
</script>
@endpush
