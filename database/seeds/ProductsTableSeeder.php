<?php

use App\EntityRelationshipModels\Shop\Category;
use App\EntityRelationshipModels\Shop\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach (Category::all() as $category) {
            // Ignore root.
            if ($category->keyword == Category::KEYWORD_ROOT) {
                continue;
            }
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
