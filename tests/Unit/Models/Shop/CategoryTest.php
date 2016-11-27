<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /** @test */
    public function it_can_create_a_category_inside_root() {
        $category = Category::createInRoot(['name' => 'Category']);
        self::assertSame('Category', $category->name);
        self::assertTrue($category->parent->isRoot());
    }

    /** @test */
    public function it_can_create_a_subcategory() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        $categoryAA = Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        self::assertSame('Category AA', $categoryAA->name);
        self::assertSame('Category A', $categoryAA->parent->name);
        self::assertTrue($categoryAA->parent->parent->isRoot());
    }

    /** @test */
    public function it_can_have_many_products() {
        $category = Category::createInRoot(['name' => 'Category']);
        $productA = new Product(['name' => 'Product A', 'price' => 10]);
        $productA->category()->associate($category);
        $productA->save();

        $productB = new Product(['name' => 'Product B', 'price' => 10]);
        $productB->category()->associate($category);
        $productB->save();

        $id = $category->id;
        $fetchedCategory = Category::find($id);
        $products = $fetchedCategory->products;
        self::assertCount(2, $products);
    }

    /** @test */
    public function it_can_have_subcategories() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        Category::createSubcategory($categoryA, ['name' => 'Category AB']);

        $fetchedCategory = Category::find($categoryA->id);
        $subcategories = $fetchedCategory->subcategories();
        self::assertCount(2, $subcategories);
    }

    /** @test */
    public function it_can_provide_the_root_category() {
        $root = Category::getRoot();
        self::assertTrue($root->isRoot());
    }

    /** @test */
    public function it_exists() {
        self::assertNotNull(new Category());
    }

    /** @test */
    public function it_has_a_default_keyword() {
        $category = new Category([
            'name' => 'Category A',
        ]);
        self::assertNotNull($category->keyword);
    }

    /** @test */
    public function it_has_a_default_keyword_based_on_its_name_with_underscores_instead_of_spaces() {
        $category = new Category([
            'name' => 'Category A',
        ]);
        self::assertSame('Category_A', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_with_ascii_alphanumeric_underscores_and_dashes_only() {
        $category = new Category([
            'name' => 'Super-Category 123 F#$k',
        ]);
        self::assertSame('Super-Category_123_F--k', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_without_accents() {
        $category = new Category([
            'name' => 'História da Computação/Régua de Cálculo',
        ]);
        self::assertSame('Historia_da_Computacao-Regua_de_Calculo', $category->keyword);
    }

    /** @test */
    public function it_has_a_description() {
        $category = Category::createInRoot(['name' => 'Category', 'description' => 'Category description.']);
        self::assertSame('Category description.', $category->description);
    }

    /** @test */
    public function it_has_a_keyword() {
        $category = new Category([
            'name' => 'Category A',
        ]);
        self::assertNotNull($category->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = Category::createInRoot(['name' => 'Test Category']);
        self::assertSame('Test Category', $category->name);
    }

    /** @test */
    public function it_may_be_a_subcategory() {
        $parent = new Category(['name' => 'Parent']);
        $child = new Category(['name' => 'Child']);
        $child->parent = $parent;
        self::assertSame('Parent', $child->parent->name);
    }

    /** @test */
    public function it_may_have_a_parent_category() {
        $parent = Category::createInRoot(['name' => 'Parent Category']);

        $child = Category::createSubcategory($parent, ['name' => 'Child Category']);

        $fetchedChild = Category::with('parent')->find($child->id);
        self::assertSame($child->id, $fetchedChild->id);
        self::assertSame($parent->id, $child->parent->id);
        self::assertSame('Parent Category', $child->parent->name);
    }

    /** @test */
    public function it_should_get_the_path_for_a_given_category() {
        $alpha = Category::createInRoot(['name' => 'Alpha']);
        $beta = Category::createSubcategory($alpha, ['name' => 'Beta']);
        $charlie = Category::createSubcategory($beta, ['name' => 'Charlie']);
        self::assertSame('/Alpha/Beta/Charlie', $charlie->getKeywordPath());
    }

    /** @test */
    public function it_should_map_to_the_correct_database_table() {
        $category = new Category();
        self::assertSame('shop_categories', $category->getTable());
    }

    /** @test */
    public function it_should_not_override_the_keyword_when_setting_a_name() {
        $category = new Category(['name' => 'Category A', 'keyword' => 'CategoryKey']);
        $category->name = 'New Name';
        self::assertSame('CategoryKey', $category->keyword);
    }

    /** @test */
    public function it_should_return_false_if_it_has_no_subcategories() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        self::assertFalse($categoryA->hasSubcategories());
    }

    /** @test */
    public function it_should_return_true_if_it_has_subcategories() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        Category::createSubcategory($categoryA, ['name' => 'Category b']);
        self::assertTrue($categoryA->hasSubcategories());
    }

    /** @test */
    public function it_should_trim_names() {
        $category = new Category(['name' => "   ABC   \n"]);
        self::assertSame('ABC', $category->name);
    }
}
