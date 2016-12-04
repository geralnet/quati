<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use Tests\Unit\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /**
     * Creates a new category inside 'root category' using the model factory.
     *
     * @param array $attributes
     * @return Category
     */
    public static function createInRoot(array $attributes = []) : Category {
        return factory(Category::class)->create($attributes);
    }

    /**
     * Creates a new subcategory inside given category using the model factory.
     *
     * @param Category $parent
     * @param array    $attributes
     * @return Category
     */
    public static function createSubcategory(Category $parent, array $attributes = []) : Category {
        $attributes['parent_id'] = $parent->id;
        return factory(Category::class)->create($attributes);
    }

    /** @test */
    public function it_can_create_a_category_inside_root() {
        $category = self::createInRoot(['name' => 'Category']);
        self::assertSame('Category', $category->name);
        self::assertTrue($category->parent->isRoot());
    }

    /** @test */
    public function it_can_create_a_subcategory() {
        $categoryA = self::createInRoot(['name' => 'Category A']);
        $categoryAA = self::createSubcategory($categoryA, ['name' => 'Category AA']);
        self::assertSame('Category AA', $categoryAA->name);
        self::assertSame('Category A', $categoryAA->parent->name);
        self::assertTrue($categoryAA->parent->parent->isRoot());
    }

    /** @test */
    public function it_can_have_many_products() {
        $category = self::createInRoot(['name' => 'Parent']);

        $productA = new Product(['name' => 'Product A', 'price' => 10]);
        $productA->category()->associate($category);
        $productA->save();

        $productB = new Product(['name' => 'Product B', 'price' => 10]);
        $productB->category()->associate($category);
        $productB->save();

        self::assertCount(2, $category->products);
    }

    /** @test */
    public function it_can_have_subcategories() {
        $parent = self::createInRoot(['name' => 'Category A']);
        self::createSubcategory($parent, ['name' => 'Category AA']);
        self::createSubcategory($parent, ['name' => 'Category AB']);

        self::assertCount(2, $parent->subcategories);
    }

    /** @test */
    public function it_can_provide_the_root_category() {
        $root = Category::getRoot();
        self::assertTrue($root->isRoot());
    }

    /** @test */
    public function it_exists() {
        $category = self::createInRoot();
        self::assertNotNull($category);
    }

    /** @test */
    public function it_has_a_default_keyword_based_on_its_name_with_underscores_instead_of_spaces() {
        $category = self::createInRoot(['name' => 'Category A']);
        self::assertSame('Category_A', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_with_ascii_alphanumeric_underscores_and_dashes_only() {
        $category = self::createInRoot(['name' => 'Super-Category 123 F#$k']);
        self::assertSame('Super-Category_123_F--k', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_without_accents() {
        $category = self::createInRoot(['name' => 'História da Computação/Régua de Cálculo']);
        self::assertSame('Historia_da_Computacao-Regua_de_Calculo', $category->keyword);
    }

    /** @test */
    public function it_has_a_description() {
        $category = self::createInRoot(['description' => 'Category description.']);
        self::assertSame('Category description.', $category->description);
    }

    /** @test */
    public function it_has_a_keyword() {
        $category = self::createInRoot();
        self::assertNotNull($category->keyword);
        self::assertNotSame('', $category->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = self::createInRoot(['name' => 'Test Category']);
        self::assertSame('Test Category', $category->name);
    }

    // /** @test */
    // public function it_has_a_path() {
    //     $category = self::createInRoot();
    //     self::assertInstanceOf(Path::class, $category->path);
    // }

    /** @test */
    public function it_may_be_a_subcategory() {
        $parent = self::createInRoot(['name' => 'Parent']);
        $child = self::createSubcategory($parent, ['name' => 'Child']);

        self::assertSame('Parent', $child->parent->name);
    }

    /** @test */
    public function it_may_have_a_parent_category() {
        $parent = self::createInRoot(['name' => 'Parent Category']);
        $child = self::createSubcategory($parent, ['name' => 'Child Category']);

        self::assertSame($parent->id, $child->parent->id);
        self::assertSame('Parent Category', $child->parent->name);
    }

    /** @test */
    public function it_provides_the_root_category_id() {
        $root = Category::getRoot();
        $actual = Category::getRootId();
        self::assertSame($actual, $root->id);
    }

    /** @test */
    public function it_should_get_the_path_for_a_given_category() {
        $alpha = self::createInRoot(['name' => 'Alpha']);
        $beta = self::createSubcategory($alpha, ['name' => 'Beta']);
        $charlie = self::createSubcategory($beta, ['name' => 'Charlie']);
        self::assertSame('/Alpha/Beta/Charlie', $charlie->getKeywordPath());
    }

    /** @test */
    public function it_should_map_to_the_correct_database_table() {
        $category = self::createInRoot();
        self::assertSame('shop_categories', $category->getTable());
    }

    /** @test */
    public function it_should_not_override_the_keyword_when_setting_a_name() {
        $category = self::createInRoot(['name' => 'Category A', 'keyword' => 'CategoryKey']);
        $category->name = 'New Name';
        self::assertSame('CategoryKey', $category->keyword);
    }

    /** @test */
    public function it_should_return_false_if_it_has_no_subcategories() {
        $categoryA = self::createInRoot(['name' => 'Category A']);
        self::assertFalse($categoryA->hasSubcategories());
    }

    /** @test */
    public function it_should_return_true_if_it_has_subcategories() {
        $categoryA = self::createInRoot(['name' => 'Category A']);
        self::createSubcategory($categoryA, ['name' => 'Category b']);
        self::assertTrue($categoryA->hasSubcategories());
    }

    /** @test */
    public function it_should_trim_names() {
        $category = self::createInRoot(['name' => "   ABC   \n"]);
        self::assertSame('ABC', $category->name);
    }
}
