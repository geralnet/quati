<?php

namespace Tests\Unit\Models\Product;

use App\Models\Product\Category;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /** @test */
    public function test_a_category_has_a_name() {
        $category = new Category();
        $category->name = 'Category Name';
        self::assertSame('Category Name', $category->name);
    }

    /** @test */
    public function test_a_category_may_be_a_subcategory() {
        $parent = new Category(['name' => 'Parent']);
        $child = new Category(['name' => 'Child']);
        $child->parent = $parent;
        self::assertSame('Parent', $child->parent->name);
    }

    /** @test */
    public function test_we_can_create_a_category_with_a_name() {
        $category = new Category(['name' => 'The Category']);
        self::assertSame('The Category', $category->name);
    }

    /** @test */
    public function test_we_can_create_a_simple_category() {
        self::assertNotNull(new Category());
    }
}
