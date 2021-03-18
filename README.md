![Laravel Database Schedule](https://banners.beyondco.de/Laravel%20Database%20Schedule.png?theme=light&packageManager=composer+require&packageName=robersonfaria%2Flaravel-database-schedule&pattern=architect&style=style_1&description=Schedule+your+tasks+through+a+dashboard+and+without+having+to+deploy+the+application.&md=1&showWatermark=1&fontSize=100px&images=calendar&widths=auto&heights=350)

# Documentation

This librarian creates a route(default: /schedule) in your application where it is possible to manage which 
schedules will be executed at any given moment, these schedules are recorded in the database and can be changed, 
activated, inactivated or deleted via the interface without the need for a new application deployment.

![List Schedules](docs/list-schedule1.png)

![Create Schedules](docs/new-schedule2.png)

![Show History Schedules](docs/show-history1.png)

## Installation

1. Run ```composer require robersonfaria/laravel-database-schedule```
2. Run ```php artisan migrate```

### Environment variables

You can set the following environment variables to configure schedules:

* SCHEDULE_TIMEZONE : The default is the same configured for the application, but if you need the schedules to run in a different timezone, it is possible to configure it with this variable
* SCHEDULE_CACHE_DRIVER : The default is `file`
* SCHEDULE_CACHE_ENABLE : The default is disabled when `APP_DEBUG=true` and enabled when `APP_DEBUG=false`

### Configurations

There are several library configuration options, to change the settings you can get the configuration file for your project
```
php artisan vendor:publish --provider="RobersonFaria\DatabaseSchedule\DatabaseSchedulingServiceProvider" --tag="config"
```

### Dashboard Authorization

Dashboard Authorization exposes a dashboard at `/schedule` URI.
This route is protected by the `viewDatabaseSchedule` gate controls access.
You are free to modify this gate as needed to restrict access to your Database Schedule Dashboard

```php
protected function gate()
{
    Gate::define('viewDatabaseSchedule', function ($user) {
        return in_array($user->email, [
            'roberson.faria@gmail.com',
        ]);
    });
}
```

### Scheduled Task Example

Create the command for your scheduled task `app/Console/Commands/test.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test {user} {initialDate} {finalDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Hello ' . $this->argument('user'));
        $this->info("Initial Date: " . $this->argument('initialDate'));
        $this->info("Final Date: " . $this->argument('finalDate'));
        return 0;
    }
}
```

Access the dashboard and the command will be listed for scheduling, create a schedule like the example below:
![Create Schedule](docs/new-schedule2.png)

Run the artisan command to run scheduled tasks
```bash
php artisan schedule:run
```

The console output will look like this
```bash
Running scheduled command: '/usr/bin/php7.2' 'artisan' command:test '1' '2021-02-10 00:00:00' '2021-04-10 00:00:00' > 'path/to/storage/logs/schedule-dcccb62f29f754dc83a86a3d0b59afb00a08fdb3.log' 2>&1
```

If you marked the sending of the output by email you will receive an email similar to this one:

![Mail Output](docs/mail-output.png)

## Known issues:

* Does not record run history when runInBackground is used

## Credits

* That library was inspired by the library [therezor/laravel-database-schedule](https://github.com/therezor/laravel-database-schedule)