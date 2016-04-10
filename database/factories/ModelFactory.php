<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\DeviceType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(['Desktop', 'Mobile', 'Tablet', 'other', 'unkown']),
    ];
});

$factory->define(App\Models\Process::class, function (Faker\Generator $faker) {
    return [
        'email'       => $faker->safeEmail,
        'start_at'    => null,
        'finished_at' => null,
        'expires_at'  => Carbon\Carbon::parse('tomorrow'),
    ];
});

$factory->define(App\Models\UserAgent::class, function (Faker\Generator $faker) {
    return [
        'ua_string'      => $faker->userAgent,
        'process_id'     => factory(App\Models\Process::class)->create()->id,
        'device_type_id' => $faker->numberBetween(1, 5),
    ];
});

$factory->define(App\Models\Report::class, function (Faker\Generator $faker) {

    $desktop = $faker->numberBetween(10, 1000);
    $tablet = $faker->numberBetween(10, 1000);
    $mobile = $faker->numberBetween(10, 1000);
    $other = $faker->numberBetween(10, 1000);
    $unkown = $faker->numberBetween(10, 1000);
    $total = $desktop + $tablet + $mobile + $other + $unkown;

    return [
        'process_id' => factory(App\Models\Process::class)->create()->id,
        'total'      => $total,
        'desktop'    => $desktop,
        'tablet'     => $tablet,
        'mobile'     => $mobile,
        'other'      => $other,
        'unkown'     => $unkown,
    ];
});
