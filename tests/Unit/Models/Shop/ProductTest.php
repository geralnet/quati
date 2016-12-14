<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Image;
use App\Models\Shop\Pathable;
use App\Models\Shop\Product;
use App\UploadedFile;
use Illuminate\Database\QueryException;
use Tests\Unit\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase {
    /**
     * Creates a new product with a path.
     *
     * @param array    $attributes
     * @param Pathable $parent
     * @return Product
     */
    public static function createWithPath(array $attributes = [], Pathable $parent = null) : Product {
        return PathTest::createWithPath(Product::class, $attributes, $parent);
    }

    /** @test */
    public function it_belongs_to_a_category() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = self::createWithPath(['name' => 'Product', 'price' => 10], $category);
        $fetchedProduct = Product::find($product->id);

        self::assertSame($product->id, $fetchedProduct->id);
        self::assertSame($category->id, $fetchedProduct->getCategory()->id);
        self::assertSame('Category', $fetchedProduct->getCategory()->name);
    }

    /** @test */
    public function it_can_be_created() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function it_can_have_a_picture_attached() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Product', 'price' => 1], $category);
        ImageTest::createWithPath(['filename' => 'Product.png'], $product, __DIR__.'/../../Fixtures/image.png');

        $image = $product->getImages()[0];
        $file = $image->file;
        self::assertInstanceOf(Image::class, $image);
        self::assertSame($image->filename, 'Product.png');
        self::assertInstanceOf(UploadedFile::class, $file);
    }

    /** @test */
    public function it_exists() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function it_has_a_description() {
        $product = ProductTest::createWithPath([
            'name'        => 'Product',
            'price'       => 100,
            'description' => 'The description.',
        ]);
        self::assertSame('The description.', $product->description);
    }

    /** @test */
    public function it_has_a_name() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Test Product', 'price' => 10], $category);
        self::assertSame('Test Product', $product->name);
    }

    /** @test */
    public function it_has_a_price() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'A Product', 'price' => 1234], $category);
        self::assertEquals(1234, $product->price);
    }

    /** @test */
    public function it_has_images() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Product', 'price' => 1], $category);
        $image = ImageTest::createWithPath(['filename' => 'Product.png'], $product, __DIR__.'/../../Fixtures/image.png');

        self::assertSame($product->getImages()[0]->id, $image->id);
    }

    /** @test */
    public function it_must_belong_to_a_category() {
        $product = new Product(['name' => 'Product without Category']);

        self::expectException(QueryException::class);
        $product->save();
    }

    /** @test */
    public function it_provides_a_image_url() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Product', 'price' => 1], $category);
        ImageTest::createWithPath(['filename' => 'Product.png'], $product, __DIR__.'/../../Fixtures/image.png');

        self::assertSame('/Category/Product/Product.png', $product->getImageURL(1));
    }

    /** @test */
    public function it_should_map_to_the_correct_database_table() {
        $product = new Product();
        self::assertSame('shop_products', $product->getTable());
    }

    /** @test */
    public function it_should_return_false_if_it_has_no_products() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        self::assertFalse($category->hasProducts());
    }

    /** @test */
    public function it_should_return_true_if_it_has_products() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        ProductTest::createWithPath([
            'name'  => 'Product',
            'price' => 1000,
        ], $category);
        self::assertTrue($category->hasProducts());
    }
}
