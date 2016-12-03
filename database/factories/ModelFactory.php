<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\User;
use Faker\Generator;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(User::class, function(Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Category::class, function(Generator $faker) {
    return [
        'parent_id'   => Category::getRoot()->id,
        'name'        => $faker->text,
        'description' => $faker->paragraph,
    ];
});
