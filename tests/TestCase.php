<?php

namespace RobersonFaria\DatabaseSchedule\Tests;

use Orchestra\Testbench\Concerns\WithFactories;
use \Orchestra\Testbench\TestCase as BaseTestCase;
use RobersonFaria\DatabaseSchedule\DatabaseScheduleApplicationServiceProvider;
use RobersonFaria\DatabaseSchedule\DatabaseSchedulingServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use WithFactories;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * add the package provider
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            DatabaseSchedulingServiceProvider::class,
            DatabaseScheduleApplicationServiceProvider::class
        ];
    }



    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
