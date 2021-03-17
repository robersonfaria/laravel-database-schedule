<?php

namespace RobersonFaria\DatabaseSchedule\Http\Services;

use App\Console\Kernel;
use Symfony\Component\Console\Input\InputArgument;

class CommandService
{

    public function get()
    {
        $commands = collect(app(Kernel::class)->all());
        $commandsKeys = $commands->keys()->toArray();
        foreach (config('database-schedule.commands.exclude') as $exclude) {
            $commandsKeys = preg_grep("/^$exclude/", $commandsKeys, PREG_GREP_INVERT);
        }
        return $commands->only($commandsKeys)
            ->map(function ($command) {
                return (object)[
                    'name' => $command->getName(),
                    'description' => $command->getDescription(),
                    'signature' => $command->getSynopsis(),
                    'arguments' => array_values(
                        array_map(function ($argument) {
                            return (object)[
                                'name' => $argument->getName(),
                                'default' => $argument->getDefault(),
                                'required' => $argument->isRequired()
                            ];
                        }, $command->getDefinition()->getArguments())
                    ),
                    'options' => array_values(
                        array_map(function ($argument) {
                            return (object)[
                                'name' => $argument->getName(),
                                'default' => $argument->getDefault(),
                                'required' => $argument->isValueRequired()
                            ];
                        }, $command->getDefinition()->getOptions())
                    ),
                ];
            });
    }
}