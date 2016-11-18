<?php

namespace Tests\Integration\Models\Product;

use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\QueryException;
use Tests\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase {
    /** @test */
    public function a_product_has_a_name() {
        $category = new Category(['name' => 'Category']);
        $category->save();

        $product = new Product();
        $product->category()->associate($category);
        $product->name = 'Test Product';
        $product->save();
        self::assertNotNull($product->save());
    }

    /** @test */
    public function a_product_must_belong_to_a_category() {
        $product = new Product(['name' => 'Product without Category']);

        self::expectException(QueryException::class);
        $product->save();
    }

    /** @test */
    public function a_product_belongs_to_a_category() {
        $category = Category::create(['name' => 'Category']);

        $product = new Product(['name' => 'Product']);
        $product->category()->associate($category);
        $product->save();

        $fetchedProduct = Product::with('category')->find($product->id);
        self::assertSame($product->id, $fetchedProduct->id);
        self::assertSame($category->id, $fetchedProduct->category->id);
        self::assertSame('Category', $fetchedProduct->category->name);
    }
}
