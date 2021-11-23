<?php
use Faker\Generator as Faker;

$factory->define(\RobersonFaria\DatabaseSchedule\Models\Schedule::class, function (Faker $faker) {
    return [
        'command' => $this->faker->word,
        'expression' => '* * * * *'
    ];
});