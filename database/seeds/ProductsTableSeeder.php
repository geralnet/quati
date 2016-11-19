<?php

use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach (Category::all() as $category) {
            for ($i = 1; $i <= 5; $i++) {
                list(, $letter) = explode(' ', $category->name);
                $product = new Product();
                $product->name = "Product {$letter} {$i}";
                $product->category()->associate($category);
                $product->save();
            }
        }
    }
}
