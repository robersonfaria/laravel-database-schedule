<?php

namespace RobersonFaria\DatabaseSchedule\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RobersonFaria\DatabaseSchedule\Models\Schedule;
use RobersonFaria\DatabaseSchedule\Tests\TestCase;

class CreateScheduleTest extends TestCase
{

    use RefreshDatabase;

    public function test_database_and_factory_works()
    {

        factory(Schedule::class, 10)->create();

        $this->assertEquals(10, Schedule::all()->count());
    }
}