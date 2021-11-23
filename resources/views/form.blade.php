@inject('commandService', 'RobersonFaria\DatabaseSchedule\Http\Services\CommandService')
<div x-data='{
    selectedCommand: "{{old('command', (isset($schedule) ? $schedule->command : ''))}}",
    commands: @json($commandService->get()),
    arguments: @json(old('params', (isset($schedule) ? $schedule->params : []))),
    options: @json(old('options', (isset($schedule) ? $schedule->options : []))),
    get commandObject() {
        if(this.commands.hasOwnProperty(this.selectedCommand)) {
            return this.commands[this.selectedCommand];
        }
        return {
            arguments: [],
            options: {
                withValue: [],
                withoutValue: []
            }
        }
    }
}'>
    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.command') }}</label>
        <select x-model="selectedCommand"
                name="command"
                class="form-control @error('command') is-invalid @enderror">
            <option value="">{{ trans('schedule::schedule.messages.select') }}</option>
            <option value="custom">{{ trans('schedule::schedule.messages.custom') }}</option>
            <template x-for="command in commands">
                <option :key="command.name"
                        :value="command.name"
                        x-text="command.name + ' - ' + command.description"
                        :selected="command.name == selectedCommand">
                </option>
            </template>
        </select>
        @error('command')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <template x-if="commandObject.arguments.length || commandObject.options.withValue.length">
        <div>
            <code>{{ trans('schedule::schedule.messages.attention-type-function') }}</code>
            <template x-if="commandObject.arguments.length">
                <div class="row my-3">
                    <div class="col-12">
                        <label>{{ trans('schedule::schedule.fields.arguments') }}:</label>
                        <template x-for="argument in commandObject.arguments">
                            <div class="ml-5 row">
                                <div class="col-8 form-group">
                                    <label x-text="argument.name"></label>
                                    <input type="text" class="form-control"
                                           :name="'params['+argument.name+'][value]'"
                                           :value="arguments && arguments.hasOwnProperty(argument.name) ? arguments[argument.name].value : argument.default"
                                           :required="argument.required">
                                </div>
                                <div class="col-4 form-group">
                                    <label>{{ trans('schedule::schedule.fields.data-type') }}</label>
                                    <select :name="'params['+argument.name+'][type]'" class="form-control">
                                        <option value="string">String</option>
                                        <option value="function"
                                                :selected="(arguments && arguments.hasOwnProperty(argument.name) ? arguments[argument.name].type : '') === 'function'">
                                            Function
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="commandObject.options.withValue.length">
                <div class="row my-3" id="options">
                    <div class="col-12">
                        <label>{{ trans('schedule::schedule.fields.options_with_value') }}:</label>
                        <template x-for="option in commandObject.options.withValue">
                            <div class="ml-5 row">
                                <div class="col-8 form-group">
                                    <label x-text="option.name"></label>
                                    <input type="text" class="form-control"
                                           :name="'options['+option.name+'][value]'"
                                           :value="options && options.hasOwnProperty(option.name) ? options[option.name].value : option.default"
                                           :required="option.required">
                                </div>
                                <div class="col-4 form-group">
                                    <label>{{ trans('schedule::schedule.fields.data-type') }}</label>
                                    <select :name="'options['+option.name+'][type]'" class="form-control">
                                        <option value="string">String</option>
                                        <option value="function"
                                                :selected="(options && options.hasOwnProperty(option.name) ? options[option.name].type : '') === 'function'">
                                            Function
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <template x-if="commandObject.options.withoutValue.length">
        <div class="row my-3">
            <div class="col-12">
                <label>{{ trans('schedule::schedule.fields.options') }}:</label>
                <template x-for="option in commandObject.options.withoutValue">
                    <div class="ml-5 row">
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input"
                                       :name="'options['+option+']'"
                                       :checked="options && options.hasOwnProperty(option)">
                                <label x-text="option" class="form-check-label" :for="option"></label>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <template x-if="selectedCommand === 'custom'">
        <div class="form-group row">
            <div class="col-12">
                <input
                   type="text"
                   placeholder="{{ trans('schedule::schedule.messages.custom-command-here')}}"
                   name="command_custom"
                   class="form-control @error('command_custom') is-invalid @enderror"/>
                @error('command_custom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </template>


    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.expression') }}</label>
        <input type="text" class="form-control @error('expression') is-invalid @enderror" name="expression"
               id="expression"
               value="{{ old('expression', $schedule->expression ?? '') }}">
        @error('expression')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(config('database-schedule.tool-help-cron-expression.enable'))
            <small id="expressionHelpBlock" class="form-text text-muted">
                <a href="{{ config('database-schedule.tool-help-cron-expression.url') }}" target="_blank">
                    {{ trans("schedule::schedule.messages.help-cron-expression") }}
                </a>
            </small>
        @endif
    </div>

    @if(config('database-schedule.enable_groups', false))
        <div class="form-group">
            <label>{{ trans('schedule::schedule.fields.groups') }}</label>
            <input type="text" class="form-control @error('groups') is-invalid @enderror" name="groups"
                   id="groups"
                   value="{{ old('groups', $schedule->groups ?? '') }}">
            @error('groups')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small id="groupsHelpBlock" class="form-text text-muted">
                {{ trans('schedule::schedule.messages.help-type', ['type' => strtolower(trans('schedule::schedule.fields.groups'))]) }}
            </small>
        </div>
    @endif

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.environments') }}</label>
        <input type="text" class="form-control @error('environments') is-invalid @enderror" name="environments"
               id="environments"
               value="{{ old('environments', $schedule->environments ?? '') }}">
        @error('environments')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small id="environmentsHelpBlock" class="form-text text-muted">
            {{ trans('schedule::schedule.messages.help-type', ['type' => strtolower(trans('schedule::schedule.fields.environments'))]) }}
        </small>
    </div>

    <div class="form-group">
        <label>{{ trans('schedule::schedule.fields.log_filename') }}</label>
        <input type="text" class="form-control @error('log_filename') is-invalid @enderror" name="log_filename"
               id="log_filename"
               value="{{ old('log_filename', $schedule->log_filename ?? '') }}">
        @error('log_filename')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small id="logFilenameHelpBlock" class="form-text text-muted">
            {{ trans('schedule::schedule.messages.help-log-filename') }}
        </small>
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
        <input type="checkbox" class="form-check-input @error('sendmail_success') is-invalid @enderror"
               name="sendmail_success"
               id="sendmail_success"
               value="1"
                {{ old('sendmail_success', $schedule->sendmail_success ?? '') == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="sendmail_success">{{ trans('schedule::schedule.fields.sendmail_success') }}</label>
        @error('sendmail_success')
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
        <input type="checkbox" class="form-check-input"
               name="log_success"
               id="log_success"
               value="1"
            {{ old('log_success', ($errors->any() ? 0 : ($schedule->log_success ?? !$errors->any()))) == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="log_success">{{ trans('schedule::schedule.fields.log_success') }}</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input"
               name="log_error"
               id="log_error"
               value="1"
            {{ old('log_error', ($errors->any() ? 0 : ($schedule->log_error ?? !$errors->any()))) == 1 ? 'checked' : '' }}>
        <label class="form-check-label"
               for="log_error">{{ trans('schedule::schedule.fields.log_error') }}</label>
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
