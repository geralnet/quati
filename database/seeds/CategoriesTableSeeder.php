<?php

use Illuminate\Database\Seeder;
use App\Models\Product\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        $categoryAB = new Category(['name' => 'Category AB']);
        $categoryAB->parent()->associate($categoryA);
        $categoryAB->save();

        $categoryB = Category::create(['name' => 'Category B']);
        $categoryBA = new Category(['name' => 'Category BA']);
        $categoryBA->parent()->associate($categoryB);
        $categoryBA->save();

        Category::create(['name' => 'Category C']);
    }
}
