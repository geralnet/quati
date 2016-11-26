<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Database\QueryException;
use Tests\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase {
    /** @test */
    public function it_belongs_to_a_category() {
        $category = Category::createInRoot(['name' => 'Category']);

        $product = new Product(['name' => 'Product', 'price' => 10]);
        $product->category()->associate($category);
        $product->save();

        $fetchedProduct = Product::with('category')->find($product->id);
        self::assertSame($product->id, $fetchedProduct->id);
        self::assertSame($category->id, $fetchedProduct->category->id);
        self::assertSame('Category', $fetchedProduct->category->name);
    }

    /** @test */
    public function it_can_be_created() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function it_exists() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function it_has_a_keyword() {
        $category = Category::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, [
            'name'    => 'Product A',
            'keyword' => 'Keyword',
            'price'   => 10,
        ]);
        self::assertSame('Keyword', $product->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = Category::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'Test Product', 'price' => 10]);
        self::assertSame('Test Product', $product->name);
    }

    /** @test */
    public function it_has_a_price() {
        $category = Category::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'A Product', 'price' => 1234]);
        self::assertEquals(1234, $product->price);
    }

    /** @test */
    public function it_must_belong_to_a_category() {
        $product = new Product(['name' => 'Product without Category']);

        self::expectException(QueryException::class);
        $product->save();
    }

    /** @test */
    public function it_should_get_the_path_for_a_given_product() {
        $alpha = Category::createInRoot(['name' => 'Alpha']);
        $beta = Category::createSubcategory($alpha, ['name' => 'Beta']);
        $charlie = Category::createSubcategory($beta, ['name' => 'Charlie']);
        $product = Product::createInCategory($charlie, ['name' => 'The Product', 'price' => 10]);
        self::assertSame('/Alpha/Beta/Charlie/The_Product', $product->getKeywordPath());
    }

    /** @test */
    public function it_should_map_to_the_correct_database_table() {
        $product = new Product();
        self::assertSame('shop_products', $product->getTable());
    }

    /** @test */
    public function it_should_not_override_the_keyword_when_setting_a_name() {
        $product = new Product(['name' => 'Product A', 'keyword' => 'ProductKey']);
        $product->name = 'New Name';
        self::assertSame('ProductKey', $product->keyword);
    }
}
