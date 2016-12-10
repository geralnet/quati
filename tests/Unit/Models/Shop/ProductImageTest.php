<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Path;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use App\UploadedFile;
use Tests\Unit\TestCase;

/**
 * Class ProductTest
 */
class ProductImageTest extends TestCase {
    public static function createWithPath(string $filename, string $namepath, Product $product) {
        $file = UploadedFile::createFromExternalFile($namepath, $filename);
        $image = new ProductImage();
        $image->file()->associate($file);
        $image->save();
        $image->path = Path::createForComponent($image, $product->path);
        return $image;
    }

    /** @test */
    public function it_has_a_product() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Product', 'price' => 1], $category);
        $image = self::createWithPath(__DIR__.'/../../Fixtures/image.png', 'Product.png', $product);

        self::assertSame($product->id, $image->getProduct()->id);
    }

    /** @test */
    public function it_has_an_uploaded_file() {
        $category = CategoryTest::createWithPath(['name' => 'Category']);
        $product = ProductTest::createWithPath(['name' => 'Product', 'price' => 1], $category);
        self::createWithPath(__DIR__.'/../../Fixtures/image.png', 'Product.png', $product);

        $images = $product->getImages();
        $file = $images[0]->file;
        self::assertSame('Product.png', $file->logical_path);
    }
}
