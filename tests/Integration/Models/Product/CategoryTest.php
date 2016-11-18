<?php

namespace Tests\Integration\Models\Product;

use App\Models\Product\Category;
use App\Models\Product\Product;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /** @test */
    public function a_category_has_a_name() {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();
        self::assertNotNull($category->save());
    }

    /** @test */
    public function a_category_may_have_a_parent_category() {
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
    public function a_category_can_have_many_products() {
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
    public function a_category_can_have_subcategories() {
        $categoryA = Category::create(['name'=>'Category A']);
        $categoryAA = Category::create(['name'=>'Category AA']);
        $categoryAB = Category::create(['name'=>'Category AB']);

        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        $categoryAB->parent()->associate($categoryA);
        $categoryAB->save();

        $fetchedCategory = Category::find($categoryA->id);
        $subcategories = $fetchedCategory->subcategories;
        self::assertCount(2, $subcategories);
    }
}
