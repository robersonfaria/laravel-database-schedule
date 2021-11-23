<?php

namespace RobersonFaria\DatabaseSchedule\Tests\Unit;

use RobersonFaria\DatabaseSchedule\Models\Schedule;
use RobersonFaria\DatabaseSchedule\Tests\TestCase;

class PhpUnitDatabaseTest extends TestCase
{
    public function testDatabaseAndFactoryWorks()
    {
        factory(Schedule::class, 2)->create();

        $this->assertEquals(2, Schedule::all()->count());
    }
}
