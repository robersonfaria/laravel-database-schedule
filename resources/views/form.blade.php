<div class="form-group">
    <label>{{ trans('schedule::schedule.fields.command') }}</label>
    @inject('commandService', 'RobersonFaria\DatabaseSchedule\Http\Services\CommandService')
    <select name="command"
            id="command"
            class="form-control @error('command') is-invalid @enderror"
            @if(isset($schedule) && $schedule->command) disabled @endif>
        <option value="">{{ trans('schedule::schedule.messages.select') }}</option>
        @foreach($commandService->get() as $command)
            <option value="{{ $command->name }}"
                    @if(old('command', $schedule->command ?? '') === $command->name) selected @endif>
                {{ $command->signature }} - {{ $command->description }}
            </option>
        @endforeach
    </select>
    @error('command')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($schedule) && $schedule->command)
        <input type="hidden" name="command" value="{{ $schedule->command }}">
    @endif
</div>

<div id="parameters" class="ml-3">
    <div class="row">
        <div class="col-12">
            <label>Parameters:</label>
            <div id="div_params" class="ml-5"></div>
        </div>
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
    <small id="expressiondHelpBlock" class="form-text text-muted">
        <a href="https://www.freeformatter.com/cron-expression-generator-quartz.html" target="_blank">
            If necessary click here and use a tool to facilitate the creation of the cron expression
        </a>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(function () {
        $("#parameters").hide();
        var commands = @json($commandService->get());
        var request = @json($schedule->params ?? old('params'));

        hasParams(commands['{{ old('command', $schedule->command ?? '') }}']);

        $("#command").change(function () {
            $("#div_params").html('');
            var command = commands[$(this).val()];
            hasParams(command);
        })

        function hasParams(command) {
            if(command !== undefined) {
                if(command.arguments.length > 0){
                    $("#parameters").show()
                } else {
                    $("#parameters").hide()
                }
                command.arguments.forEach(function (argument) {
                    $("#div_params").append(addField(argument));
                })
            }
        }

        function addField(argument) {
            var value = argument.default;
            if(argument.name !== undefined && request!== null && request[argument.name] !== undefined) {
                value = request[argument.name]
            }
            var label = $("<label/>").html(argument.name + ":")
            var input = $('<input/>')
                .attr('type', 'text')
                .attr('name', 'params[' + argument.name + ']')
                .attr('id', argument.name)
                .addClass('form-control')
                .attr('required', argument.required)
                .val(value)
            return $('<div/>').addClass('form-group')
                .append(label)
                .append(input)
        }
    })
</script>