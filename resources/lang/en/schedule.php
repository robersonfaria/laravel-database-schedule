<?php
return [
    'titles' => [
        'list' => 'Schedule List',
        'create' => 'Create new schedule',
        'edit' => 'Edit schedule',
        'show' => 'Show run history'
    ],
    'fields' => [
        'command' => 'Command',
        'arguments' => 'Arguments',
        'options' => 'Options',
        'options_with_value' => 'Options with Value',
        'expression' => 'Cron Expression',
        'output' => 'Output',
        'even_in_maintenance_mode' => 'Even in maintenance mode',
        'without_overlapping' => 'Without overlapping',
        'on_one_server' => 'Execute scheduling only on one server',
        'webhook_before' => 'URL Before',
        'webhook_after' => 'URL After',
        'email_output' => 'Email for sending output',
        'sendmail_success' => 'Send email in case of success to execute the command',
        'sendmail_error' => 'Send email in case of failure to execute the command',
        'log_success' => 'Write command output into history table in case of success to execute the command',
        'log_error' => 'Write command output into history table in case of failure to execute the command',
        'status' => 'Status',
        'actions' => 'Actions',
        'data-type' => 'Data type',
        'run_in_background' => 'Run in background',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ],
    'messages' => [
        'no-records-found' => 'No records found.',
        'save-success' => 'Data saved successfully.',
        'save-error' => 'Error saving data.',
        'timezone' => 'All schedules will be executed in the timezone: ',
        'select' => 'Select a command',
        'custom' => 'Custom Command',
        'custom-command-here' => 'Custom Command here (e.g. `cat /proc/cpuinfo` or `artisan db:migrate`)',
        'help-cron-expression' => 'If necessary click here and use a tool to facilitate the creation of the cron expression',
        'attention-type-function' => "ATTENTION: parameters of the type 'function' are executed before the execution of the scheduling and its return is passed as parameter. Use with care, it can break your job"
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive'
    ],
    'buttons' => [
        'create' => 'Create New',
        'edit' => 'Edit',
        'back' => 'Back',
        'save' => 'Save',
        'inactivate' => 'Inactivate',
        'activate' => 'Activate',
        'delete' => 'Delete',
        'history' => 'History',
    ],
    'never' => 'Never',
];
