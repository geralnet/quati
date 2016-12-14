<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Image;
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
        $category = CategoryTest::createWithPath(
            $attributes,
            is_null($parent) ? null : $parent->component
        );
        return $category->path;
    }

    public static function createForProduct(array $attributes = [], Path $parent = null) {
        $product = ProductTest::createWithPath(
            $attributes,
            is_null($parent) ? null : $parent->component
        );
        return $product->path;
    }

    /**
     * @deprecated
     * @param string[] $attributes
     * @param Pathable $component
     * @return Path
     */
    public static function createPath() : Path {
        return CategoryTest::createWithPath()->path;
    }

    public static function createWithPath(string $pathable, array $attributes = [], Pathable $parent = null) {
        $pathable = factory($pathable)->create($attributes);
        $pathable->path = Path::createForComponent($pathable, is_null($parent) ? null : $parent->path);
        return $pathable;
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
        $component = self::createForCategory(['name' => 'NewPath']);
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
        $category = factory(Category::class)->create();
        $path = Path::createForComponent($category);

        self::assertSame($category->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product() {
        $product = factory(Product::class)->create();
        $path = Path::createForComponent($product);

        self::assertSame($product->id, $path->component->getId());
    }

    /** @test */
    public function it_may_have_a_product_image() {
        $image = factory(Image::class)->create();
        $path = Path::createForComponent($image);

        self::assertSame($image->id, $path->component->getId());
    }
}
