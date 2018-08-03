<?php

use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    return [
        'link' => $faker->url(),
        'slug' => str_random(25),
        'title' => $faker->sentence()
    ];
});
