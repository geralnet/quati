<?php

namespace Tests\Integration\Models\Product;

use App\Models\Product\Category;
use App\Models\Product\CategoryTree;
use App\Models\Product\Product;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /** @test */
    public function it_has_a_name() {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();
        self::assertNotNull($category->save());
    }

    /** @test */
    public function it_may_have_a_parent_category() {
        $parent = new Category(['name' => 'Parent Category']);
        $parent->save();

        $child = new Category(['name' => 'Child Category']);
        $child->parent()->associate($parent);
        $child->save();

        $fetchedChild = Category::with('parent')->find($child->id);
        self::assertSame($child->id, $fetchedChild->id);
        self::assertSame($parent->id, $child->parent->id);
        self::assertSame('Parent Category', $child->parent->name);
    }

    /** @test */
    public function it_can_have_many_products() {
        $category = new Category(['name' => 'Category']);
        $category->save();

        $productA = new Product(['name' => 'Product A']);
        $productA->category()->associate($category);
        $productA->save();

        $productB = new Product(['name' => 'Product A']);
        $productB->category()->associate($category);
        $productB->save();

        $fetchedCategory = Category::find($category->id);
        $products = $fetchedCategory->products;
        self::assertCount(2, $products);
    }

    /** @test */
    public function it_can_have_subcategories() {
        $categoryA = Category::create(['name' => 'Category A']);
        $categoryAA = Category::create(['name' => 'Category AA']);
        $categoryAB = Category::create(['name' => 'Category AB']);

        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        $categoryAB->parent()->associate($categoryA);
        $categoryAB->save();

        $fetchedCategory = Category::find($categoryA->id);
        $subcategories = $fetchedCategory->subcategories;
        self::assertCount(2, $subcategories);
    }

    /** @test */
    public function it_can_provide_all_root_categories() {
        $this->generateData();

        Category::create(['name' => 'Category C']);

        $expected = ['Category A', 'Category B', 'Category C'];
        $actual = [];
        foreach (Category::getRootCategories() as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }

    /**
     * Creates some data used when testing fetched results.
     */
    private function generateData() {
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        $categoryAB = new Category(['name' => 'Category AB']);
        $categoryAB->parent()->associate($categoryA);
        $categoryAB->save();

        $categoryB = Category::create(['name' => 'Category B']);
        $categoryBA = new Category(['name' => 'Category BA']);
        $categoryBA->parent()->associate($categoryB);
        $categoryBA->save();
    }
}
