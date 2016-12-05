<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\Models\Shop\Path;
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

$factory->define(Path::class, function(Generator $faker) {
    return [
        'pathname' => $faker->text(20),
    ];
});

$factory->define(Category::class, function(Generator $faker) {
    return [
        'parent_id'   => Category::getRoot()->id,
        'name'        => $faker->text(20),
        'description' => $faker->paragraph,
    ];
});

$factory->define(Product::class, function(Generator $faker) {
    return [
        'name'        => $faker->text(20),
        'price'       => $faker->numberBetween(1, 10000),
        'category_id' => Category::getRootId(),
    ];
});

$factory->define(ProductImage::class, function(Generator $faker) {
    $product = factory(Product::class)->create();
    $file = factory(UploadedFile::class)->create();

    return [
        'product_id' => $product->id,
        'file_id'    => $file->id,
    ];
});

$factory->define(UploadedFile::class, function(Generator $faker) {
    $sha1 = UploadedFile::create_sha1_path(str_repeat('0', 40));
    return [
        'logical_path' => '/'.$faker->word,
        'real_path'    => $sha1,
    ];
});
