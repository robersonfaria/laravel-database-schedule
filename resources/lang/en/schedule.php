<?php
return [
    'titles' => [
        'list' => 'Schedule List',
        'create' => 'Create new schedule',
        'edit' => 'Edit schedule',
        'show' => 'Show run history',
        'back_to_application' => 'Back to application'
    ],
    'fields' => [
        'command' => 'Command',
        'arguments' => 'Arguments',
        'options' => 'Options',
        'options_with_value' => 'Options with Value',
        'expression' => 'Cron Expression',
        'log_filename' => 'Log filename',
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
        'updated_at' => 'Updated At',
        'never' => 'Never',
        'groups' => 'Groups',
        'environments' => 'Environments'
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
        'help-log-filename' => 'If log file is set, the log messages from this cron are written to storage/logs/<log filename>.log',
        'help-type' => 'Multiple :type can be specified separated by commas',
        'attention-type-function' => "ATTENTION: parameters of the type 'function' are executed before the execution of the scheduling and its return is passed as parameter. Use with care, it can break your job",
        'delete_cronjob' => 'Delete cronjob',
        'delete_cronjob_confirm' => 'Do you really want to delete the cronjob ":cronjob"?'
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'trashed' => 'Trashed',
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
        'cancel' => 'Cancel',
        'restore' => 'Restore'
    ],
    'validation' => [
        'cron' => 'The field must be filled in the cron expression format.',
        'regex' => trans('validation.alpha_dash') . ' ' . 'Comma is also allowed.'
    ]
];
