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
            'expression' => "required|cron",
            'webhook_before' => 'nullable|url',
            'webhook_after' => 'nullable|url',
            'email_output' => 'requiredIf:sendmail_error,1|nullable|email',
        ];
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
            'validation.cron' => 'The field must be filled in the cron expression format.'
        ];
    }
}
