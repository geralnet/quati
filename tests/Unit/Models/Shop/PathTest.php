<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Pathable;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Tests\Unit\Models\Shop\CategoryTest;
use Tests\Unit\TestCase;

/**
 * Class PathTest
 */
class PathTest extends TestCase {
    /**
     * @param string[] $attributes
     * @param Pathable $component
     * @return Path
     */
    public static function createPath(array $attributes = [], Pathable $component = null) : Path {
        if (is_null($component)) {
            $component = CategoryTest::createInRoot();
        }
        $attributes['component_id'] = $component->id;
        $attributes['component_type'] = get_class($component);
        return factory(Path::class)->create($attributes);
    }

    /** @test */
    public function it_exists() {
        $component = self::createPath();
        self::assertNotNull($component);
    }

    /** @test */
    public function it_has_a_pathname() {
        $component = self::createPath(['pathname' => 'Path']);
        self::assertSame('Path', $component->pathname);
    }

    /** @test */
    public function it_has_a_root_path() {
        $root = Path::getRoot();
        self::assertSame('', $root->pathname);
    }

    /** @test */
    public function it_is_created_with_a_pathable_component() {
        $category = factory(Category::class)->create(['name' => 'The Category']);
        $path = Path::createForComponent($category);
        self::assertSame($category->id, $path->component->id);
    }

    /** @test */
    public function it_may_have_a_category() {
        $category = CategoryTest::createInRoot();
        $path = self::createPath([], $category);

        self::assertSame($category->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product() {
        $product = factory(Product::class)->create();
        $path = self::createPath([], $product);

        self::assertSame($product->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product_image() {
        $image = factory(ProductImage::class)->create();
        $path = self::createPath([], $image);

        self::assertSame($image->id, $path->component->getId());
    }
}
