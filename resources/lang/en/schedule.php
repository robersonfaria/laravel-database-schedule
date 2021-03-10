<?php
return [
    'titles' => [
        'list' => 'Schedule List',
        'create' => 'Create new schedule'
    ],
    'fields' => [
        'command' => 'Command',
        'params' => 'Parameters',
        'expression' => 'Cron Expression',
        'even_in_maintenance_mode' => 'Even in maintenance mode',
        'without_overlapping' => 'Without overlapping',
        'on_one_server' => 'Execute scheduling only on one server',
        'webhook_before' => 'URL Before',
        'webhook_after' => 'URL After',
        'email_output' => 'Email for sending output',
        'sendmail_error' => 'Send email in case of failure to execute the command',
        'status' => 'Status',
        'actions' => 'Actions'
    ],
    'messages' => [
        'no-records-found' => 'No records found.',
        'save-success' => 'Data saved successfully.',
        'save-error' => 'Error saving data.',
        'timezone' => 'All schedules will be executed in the timezone: ',
        'select' => 'Select a command',
        'help-cron-expression' => 'If necessary click here and use a tool to facilitate the creation of the cron expression'
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
    ]
];