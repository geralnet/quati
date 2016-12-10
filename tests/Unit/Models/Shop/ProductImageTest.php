<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use App\UploadedFile;
use Tests\Unit\Models\Shop\CategoryTest;
use Tests\Unit\TestCase;

/**
 * Class ProductTest
 */
class ProductImageTest extends TestCase {
    /** @test */
    public function it_has_a_product() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = Product::createInCategory($category, ['name' => 'Product', 'price' => 1]);
        $file = UploadedFile::createFromExternalFile('/images/product.png', __DIR__.'/../../Fixtures/image.png');

        $image = new ProductImage();
        $image->product()->associate($product);
        $image->file()->associate($file);
        $image->save();

        self::assertSame($product->id, $image->product->id);
    }

    /** @test */
    public function it_has_an_uploaded_file() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
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
}
