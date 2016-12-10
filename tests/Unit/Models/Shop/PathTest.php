<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Pathable;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Tests\Unit\TestCase;

/**
 * Class PathTest
 */
class PathTest extends TestCase {
    public static function createForCategory(array $attributes = [], Path $parent = null) {
        $category = CategoryTest::createWithPath($attributes);
        return Path::createForComponent($category, $parent);
    }

    public static function createForProduct(array $attributes = [], Path $parent = null) {
        $product = ProductTest::createWithPath($attributes);
        return Path::createForComponent($product, $parent);
    }

    /**
     * @deprecated
     * @param string[] $attributes
     * @param Pathable $component
     * @return Path
     */
    public static function createPath(Pathable $component = null) : Path {
        if (is_null($component)) {
            $component = CategoryTest::createWithPath();
        }
        $attributes['component_id'] = $component->id;
        $attributes['component_type'] = get_class($component);
        return Path::createForComponent($component);
    }

    /** @test */
    public function it_can_have_subpaths() {
        $parent = Path::createForComponent(factory(Category::class)->create());
        $child1 = Path::createForComponent(factory(Category::class)->create(), $parent);
        $child2 = Path::createForComponent(factory(Category::class)->create(), $parent);

        self::assertSame($child1->id, $parent->subpaths[0]->id);
        self::assertSame($child2->id, $parent->subpaths[1]->id);
    }

    /** @test */
    public function it_exists() {
        $component = self::createPath();
        self::assertNotNull($component);
    }

    /** @test */
    public function it_has_a_fullpath() {
        $parent = Path::createForComponent(factory(Category::class)->create(['name' => 'Alpha']));
        $child = Path::createForComponent(factory(Category::class)->create(['name' => 'Bravo']), $parent);
        $grandchild = Path::createForComponent(factory(Category::class)->create(['name' => 'Charlie']), $child);

        self::assertSame('/Alpha', $parent->fullpath);
        self::assertSame('/Alpha/Bravo', $child->fullpath);
        self::assertSame('/Alpha/Bravo/Charlie', $grandchild->fullpath);
    }

    /** @test */
    public function it_has_a_parent_path() {
        $parent = Path::createForComponent(factory(Category::class)->create());
        $child = Path::createForComponent(factory(Category::class)->create(), $parent);

        self::assertSame($parent->id, $child->parent->id);
    }

    /** @test */
    public function it_has_a_pathname() {
        $component = self::createPath(factory(Category::class)->create(['name' => 'NewPath']));
        self::assertSame('NewPath', $component->pathname);
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
        $category = CategoryTest::createWithPath();
        $path = self::createPath($category);

        self::assertSame($category->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product() {
        $product = factory(Product::class)->create();
        $path = self::createPath($product);

        self::assertSame($product->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product_image() {
        $image = factory(ProductImage::class)->create();
        $path = self::createPath($image);

        self::assertSame($image->id, $path->component->getId());
    }
}
