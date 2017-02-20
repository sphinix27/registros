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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => $faker->username,
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => $faker->randomElement(['admin','user']),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Turno::class, function (Faker\Generator $faker) {

    return [
        'fecha' => \Carbon\Carbon::now(),
        'user_id' => 1        
    ];
});

$factory->define(App\Delito::class, function (Faker\Generator $faker) {

    return [
    	'articulo' => $faker->numberBetween(1, 300),
    	'inciso' => $faker->randomElement(['bis','ter','quarter', 'a', 'b', 'c', '']),
    	'nombre' => $faker->name
    ];
});

$factory->define(App\Estado::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->name
    ];
});

$factory->define(App\Persona::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->name
    ];
});

$factory->define(App\Registro::class, function (Faker\Generator $faker) {

    return [
        'caso' => $faker->numerify('FIS 1######'),
        'fecha' => \Carbon\Carbon::now(),
        'situacion_procesal' => $faker->randomElement(['APR', 'DIS', 'LIB']),
        'observaciones' => $faker->sentence,
    ];
});
