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
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\DeviceType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Models\Process::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'expires_at' => null,
        'start_at' => null,
        'finished_at' => null
    ];
});

$factory->define(App\Models\UserAgent::class, function (Faker\Generator $faker) {
    return [
        'ua_string' => $faker->userAgent,
        'process_id' => factory(App\Models\Process::class)->create()->id,
        'device_type_id' => factory(App\Models\DeviceType::class)->create()->id
    ];
});
