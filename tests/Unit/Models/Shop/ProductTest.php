<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use App\UploadedFile;
use Illuminate\Database\QueryException;
use Tests\Unit\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase {
    /**
     * Creates a new product using the model factory.
     *
     * @param array $attributes
     * @return Product
     */
    public static function createInRoot(array $attributes = []) : Product {
        return factory(Product::class)->create($attributes);
        // TODO delme ?
    }

    public static function createInCategory(Category $parent, array $attributes = []) : Product {
        $attributes['category_id'] = $parent->id;
        return factory(Product::class)->create($attributes);
        // TODO fixme ?
    }

    /** @test */
    public function it_belongs_to_a_category() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);

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
    public function it_can_have_a_picture_attached() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'Product', 'price' => 1]);
        $file = UploadedFile::createFromExternalFile('/images/product.png', __DIR__.'/../../Fixtures/image.png');

        $image = new ProductImage();
        $image->product()->associate($product);
        $image->file()->associate($file);
        $image->save();

        $images = $product->images[0];
        $file = $images->file;
        self::assertSame('/images/product.png', $file->logical_path);
    }

    /** @test */
    public function it_exists() {
        self::assertNotNull(new Product());
    }

    /** @test */
    public function it_has_a_description() {
        $product = Product::createInCategory(Category::getRoot(), [
            'name'        => 'Product',
            'price'       => 100,
            'description' => 'The description.',
        ]);
        self::assertSame('The description.', $product->description);
    }

    /** @test */
    public function it_has_a_keyword() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, [
            'name'    => 'Product A',
            'keyword' => 'Keyword',
            'price'   => 10,
        ]);
        self::assertSame('Keyword', $product->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'Test Product', 'price' => 10]);
        self::assertSame('Test Product', $product->name);
    }

    /** @test */
    public function it_has_a_price() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'A Product', 'price' => 1234]);
        self::assertEquals(1234, $product->price);
    }

    /** @test */
    public function it_has_images() {
        $category = CategoryTest::createInRoot(['name' => 'The Category']);
        $product = Product::createInCategory($category, ['name' => 'The Product', 'price' => 1]);
        $file = UploadedFile::createFromExternalFile('/images/product.png', __DIR__.'/../../Fixtures/image.png');

        $image = new ProductImage();
        $image->product()->associate($product);
        $image->file()->associate($file);
        $image->save();

        self::assertSame($product->images[0]->id, $image->id);
    }

    /** @test */
    public function it_must_belong_to_a_category() {
        $product = new Product(['name' => 'Product without Category']);

        self::expectException(QueryException::class);
        $product->save();
    }

    /** @test */
    public function it_provides_a_image_url() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'Product', 'price' => 1]);
        $file = UploadedFile::createFromExternalFile('/images/product.png', __DIR__.'/../../Fixtures/image.png');

        $image = new ProductImage();
        $image->product()->associate($product);
        $image->file()->associate($file);
        $image->save();

        self::assertSame('/@images/product.png', $product->getImageURL(1));
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

    /** @test */
    public function it_should_return_false_if_it_has_no_products() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        self::assertFalse($category->hasProducts());
    }

    /** @test */
    public function it_should_return_true_if_it_has_products() {
        $category = CategoryTest::createInRoot(['name' => 'Category']);
        Product::createInCategory($category, [
            'name'  => 'Product',
            'price' => 1000,
        ]);
        self::assertTrue($category->hasProducts());
    }
}
