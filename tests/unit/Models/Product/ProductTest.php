<?php

use App\Models\Product\Category;
use App\Models\Product\Product;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase {
    /** @test */
    public function test_we_can_create_a_simple_product() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function test_we_can_create_a_product_with_a_name() {
        $product = new Product(['name' => 'The Name']);
        self::assertSame('The Name', $product->name);
    }

    /** @test */
    public function test_a_product_has_a_name() {
        $product = new Product();
        $product->name = 'Product Name';
        self::assertSame('Product Name', $product->name);
    }

    /** @test */
    public function test_a_product_belongs_to_a_category() {
        $product = new Product();
        $category = new Category();
        $product->category = $category;
        self::assertSame($category, $product->category);
    }
}
