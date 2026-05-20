<?php

namespace RobersonFaria\DatabaseSchedule\Http\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use ReflectionMethod;

class KernelExtractorService
{

    public function extract(): array
    {
        $kernel = App::make(Kernel::class);
        $schedule = App::make(Schedule::class);

        $method = new ReflectionMethod($kernel, 'schedule');
        $method->setAccessible(true);
        $method->invoke($kernel, $schedule);

        $extractedSchedules = [];

        foreach ($schedule->events() as $event) {
            $extractedSchedules[] = $this->parseEvent($event);
        }

        return $extractedSchedules;
    }

    private function parseEvent($event): array
    {
        $command = $this->getCommand($event);

        // Get cron expression
        $expression = $event->getExpression();

        // Get configurations
        $withoutOverlapping = $this->getProperty($event, 'withoutOverlapping');
        $onOneServer = $this->getProperty($event, 'onOneServer');
        $environments = $this->getEnvironments($event);

        return [
            'command' => $command['command'],
            'params' => $command['params'],
            'expression' => $expression,
            'without_overlapping' => $withoutOverlapping,
            'on_one_server' => $onOneServer,
            'environments' => $environments,
            'status' => true,
        ];
    }

    private function getCommand($event): array
    {
        if (!empty($event->description)) {
            return [
                'command' => $event->description,
                'params'  => []
            ];
        }

        $fullCommand = $event->command ?? '';

        $fullCommand = str_replace(["'/usr/local/bin/php'", "'artisan'"], '', $fullCommand);
        $fullCommand = trim(preg_replace('/\s+/', ' ', $fullCommand));

        $parts = explode(' ', $fullCommand);
        $command = $parts[0] ?? '';
        $params  = array_slice($parts, 1);

        return [
            'command' => $command,
            'params'  => $this->formatParams($params),
        ];
    }
    
    private function formatParams(array $params): array
    {
        $formatted = [];
        foreach ($params as $index => $param) {
            $formatted["arg{$index}"] = ['value' => $param];
        }
        return $formatted;
    }
    
    private function getProperty($event, string $property)
    {
        try {
            $reflection = new ReflectionClass($event);
            $prop = $reflection->getProperty($property);
            $prop->setAccessible(true);

            return $prop->getValue($event);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function getEnvironments($event): ?string
    {
        $environments = $this->getProperty($event, 'environments');
        return $environments ? implode(',', $environments) : null;
    }
}
