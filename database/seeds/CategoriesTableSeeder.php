<?php

use App\Models\Shop\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        $categoryAA = Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        Category::createSubcategory($categoryAA, ['name' => 'Category AAA']);
        Category::createSubcategory($categoryA, ['name' => 'Category AB']);

        $categoryB = Category::createInRoot(['name' => 'Category B']);
        Category::createSubcategory($categoryB, ['name' => 'Category BA']);

        Category::createInRoot(['name' => 'Category C']);
    }
}
