<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Pathable;
use App\Models\Shop\Product;
use Tests\Unit\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /**
     * Creates a new category with a path.
     *
     * @param array    $attributes
     * @param Pathable $parent
     * @return Category
     */
    public static function createWithPath(array $attributes = [], Pathable $parent = null) : Category {
        return PathTest::createWithPath(Category::class, $attributes, $parent);
    }

    /** @test */
    public function it_can_create_a_category_inside_root() {
        $category = self::createWithPath(['name' => 'Category']);
        self::assertSame('Category', $category->name);
        self::assertTrue($category->getParent()->isRoot());
    }

    /** @test */
    public function it_can_create_a_subcategory() {
        $categoryA = self::createWithPath(['name' => 'Category A']);
        $categoryAA = self::createWithPath(['name' => 'Category AA'], $categoryA);
        self::assertSame('Category AA', $categoryAA->name);
        self::assertSame('Category A', $categoryAA->getParent()->name);
        self::assertTrue($categoryAA->getParent()->getParent()->isRoot());
    }

    /** @test */
    public function it_can_have_many_products() {
        $category = self::createWithPath(['name' => 'Parent']);

        ProductTest::createWithPath(
            ['name' => 'Product A', 'price' => 10],
            $category
        );
        ProductTest::createWithPath(
            ['name' => 'Product B', 'price' => 20],
            $category
        );

        self::assertCount(2, $category->getProducts());
        foreach ($category->getProducts() as $product) {
            self::assertInstanceOf(Product::class, $product);
        }
    }

    /** @test */
    public function it_can_have_subcategories() {
        $parent = self::createWithPath(['name' => 'Category A']);
        self::createWithPath(['name' => 'Category AA'], $parent);
        self::createWithPath(['name' => 'Category AB'], $parent);

        self::assertCount(2, $parent->getSubcategories());
        foreach ($parent->getSubcategories() as $subcategory) {
            self::assertInstanceOf(Category::class, $subcategory);
        }
    }

    /** @test */
    public function it_can_provide_the_root_category() {
        $root = Category::getRoot();
        self::assertTrue($root->isRoot());
    }

    /** @test */
    public function it_exists() {
        $category = self::createWithPath();
        self::assertNotNull($category);
    }

    /** @test */
    public function it_has_a_description() {
        $category = self::createWithPath(['description' => 'Category description.']);
        self::assertSame('Category description.', $category->description);
    }

    /** @test */
    public function it_has_a_name() {
        $category = self::createWithPath(['name' => 'Test Category']);
        self::assertSame('Test Category', $category->name);
    }

    /** @test */
    public function it_has_a_path() {
        $category = self::createWithPath();
        self::assertInstanceOf(Path::class, $category->path);
    }

    /** @test */
    public function it_may_be_a_subcategory() {
        $parent = self::createWithPath(['name' => 'Parent']);
        $child = self::createWithPath(['name' => 'Child'], $parent);

        self::assertSame('Parent', $child->getParent()->name);
    }

    /** @test */
    public function it_may_have_a_parent_category() {
        $parent = self::createWithPath(['name' => 'Parent Category']);
        $child = self::createWithPath(['name' => 'Child Category'], $parent);

        self::assertInstanceOf(Category::class, $child->getParent());
        self::assertSame($parent->id, $child->getParent()->id);
        self::assertSame('Parent Category', $child->getParent()->name);
    }

    /** @test */
    public function it_should_map_to_the_correct_database_table() {
        $category = self::createWithPath();
        self::assertSame('shop_categories', $category->getTable());
    }

    /** @test */
    public function it_should_return_false_if_it_has_no_subcategories() {
        $categoryA = self::createWithPath(['name' => 'Category A']);
        self::assertFalse($categoryA->hasSubcategories());
    }

    /** @test */
    public function it_should_return_true_if_it_has_subcategories() {
        $categoryA = self::createWithPath(['name' => 'Category A']);
        self::createWithPath(['name' => 'Category b'], $categoryA);
        self::assertTrue($categoryA->hasSubcategories());
    }
}
