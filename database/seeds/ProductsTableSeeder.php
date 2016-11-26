<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
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
            if ($category->isRoot()) {
                continue;
            }
            for ($i = 1; $i <= 5; $i++) {
                list(, $letter) = explode(' ', $category->name);
                Product::createInCategory($category, [
                    'name'  => "Product {$letter} {$i}",
                    'price' => $i * 99,
                ]);
            }
        }
    }
}
