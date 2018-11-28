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

$factory->define(App\Model\Link::class, function (Faker\Generator $faker) {
    $id = random_int(0,1000000);
    $url = $faker->randomElement($array = array ('http://','https://')).$faker->domainName.'/'.str_random(10);
    return [
        'id' => $id,
        'url' => $url,
        'key' => str_replace('=', '', base64_encode($id)),
    ];
});
