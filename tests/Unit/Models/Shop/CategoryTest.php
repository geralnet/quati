<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Tests\Unit\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    public static function createInRoot(array $attributes) {
        return self::createSubcategory(Category::getRoot(), $attributes);
    }

    public static function createSubcategory(Category $parent, array $attributes) {
        $category = new Category($attributes);
        $category->parent()->associate($parent);
        $category->save();
        return $category;
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
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Parent']);

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
        $category = factory(Category::class)->create();
        self::assertNotNull($category);
    }

    /** @test */
    public function it_has_a_default_keyword_based_on_its_name_with_underscores_instead_of_spaces() {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'Category A',
        ]);
        self::assertSame('Category_A', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_with_ascii_alphanumeric_underscores_and_dashes_only() {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'Super-Category 123 F#$k',
        ]);
        self::assertSame('Super-Category_123_F--k', $category->keyword);
    }

    /** @test */
    public function it_has_a_default_keywork_without_accents() {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'História da Computação/Régua de Cálculo',
        ]);
        self::assertSame('Historia_da_Computacao-Regua_de_Calculo', $category->keyword);
    }

    /** @test */
    public function it_has_a_description() {
        /** @var Category $category */
        $category = factory(Category::class)->create(['description' => 'Category description.']);
        self::assertSame('Category description.', $category->description);
    }

    /** @test */
    public function it_has_a_keyword() {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        self::assertNotNull($category->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = factory(Category::class)->create(['name' => 'Test Category']);
        self::assertSame('Test Category', $category->name);
    }

    /** @test */
    public function it_may_be_a_subcategory() {
        /** @var Category $parent */
        $parent = factory(Category::class)->create(['name' => 'Parent']);
        /** @var Category $child */
        $child = factory(Category::class)->make(['name' => 'Child']);
        $child->parent()->associate($parent);
        $child->save();

        self::assertSame('Parent', $child->parent->name);
    }

    /** @test */
    public function it_may_have_a_parent_category() {
        /** @var Category $category */
        $parent = factory(Category::class)->create(['name' => 'Parent Category']);

        /** @var Category $child */
        $child = factory(Category::class)->make(['name' => 'Child Category']);
        $child->parent()->associate($parent);
        $child->save();

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
        $category = new Category();
        self::assertSame('shop_categories', $category->getTable());
    }

    /** @test */
    public function it_should_not_override_the_keyword_when_setting_a_name() {
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category A', 'keyword' => 'CategoryKey']);
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
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => "   ABC   \n"]);
        self::assertSame('ABC', $category->name);
    }
}
