<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\Models\Shop\Image;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use App\UploadedFile;
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
        'name'        => $faker->text(20),
        'description' => $faker->paragraph,
    ];
});

$factory->define(Product::class, function(Generator $faker) {
    return [
        'name'  => $faker->text(20),
        'price' => $faker->numberBetween(1, 10000),
    ];
});

$factory->define(ProductImage::class, function(Generator $faker) {
    $file = factory(UploadedFile::class)->create();

    return [
        'file_id' => $file->id,
    ];
});

$factory->define(Image::class, function(Generator $faker) {
    return [
        'filename' => $faker->word.'.jpg',
    ];
});

$factory->define(UploadedFile::class, function(Generator $faker) {
    $sha1 = UploadedFile::create_sha1_path(str_repeat('0', 40));
    return [
        'logical_path' => '/'.$faker->word,
        'real_path'    => $sha1,
    ];
});
