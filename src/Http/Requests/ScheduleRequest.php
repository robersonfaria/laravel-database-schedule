<?php

namespace RobersonFaria\DatabaseSchedule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'command' => 'required',
            'command_custom' => 'nullable|string|required_if:command,custom',
            'expression' => "required|cron",
            'webhook_before' => 'nullable|url',
            'webhook_after' => 'nullable|url',
            'email_output' => 'requiredIf:sendmail_error,1|requiredIf:sendmail_success,1|nullable|email',
            'log_filename' => 'nullable|alpha_dash',
            'groups' => 'nullable|regex:/^[A-Za-z-_0-9,]*$/',
            'environments' => 'nullable|regex:/^[A-Za-z-_0-9,]*$/',
            'params' => 'present|array',
            'options' => 'present|array',
            'sendmail_success' => 'present|boolean',
            'sendmail_error' => 'present|boolean',
            'log_success' => 'present|boolean',
            'log_error' => 'present|boolean',
            'even_in_maintenance_mode' => 'present|boolean',
            'without_overlapping' => 'present|boolean',
            'on_one_server' => 'present|boolean',
            'run_in_background' => 'present|boolean'
        ];
    }

    protected function prepareForValidation()
    {
        $fields = [
            'params' => [],
            'options' => [],
            'sendmail_success' => false,
            'sendmail_error' => false,
            'log_success' => false,
            'log_error' => false,
            'even_in_maintenance_mode' => false,
            'without_overlapping' => false,
            'on_one_server' => false,
            'run_in_background' => false
        ];
        foreach ($fields as $field => $defaultValue) {
            $this->merge([$field => $this->input($field) ?? $defaultValue]);
        }
    }

    public function attributes()
    {
        return [
            'command' => strtolower(trans('schedule::schedule.fields.command')),
            'arguments' => strtolower(trans('schedule::schedule.fields.arguments')),
            'options' => strtolower(trans('schedule::schedule.fields.options')),
            'expression' => strtolower(trans('schedule::schedule.fields.expression'))
        ];
    }

    public function messages()
    {
        return [
            'groups.regex' => trans('schedule::schedule.validation.regex'),
            'expression.cron' => trans('schedule::schedule.validation.cron'),
            'environments.regex' => trans('schedule::schedule.validation.regex'),
        ];
    }
}
