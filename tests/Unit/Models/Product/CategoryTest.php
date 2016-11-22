<?php

use App\Models\Product\Category;
use App\Models\Product\Product;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase {
    /** @var Category[] */
    private $generatedCategories = null;

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
    public function it_has_a_keyword() {
        $category = new Category([
            'name' => 'Category A',
        ]);
        self::assertNotNull($category->keyword);
    }

    /** @test */
    public function it_has_a_name() {
        $category = Category::create(['name' => 'Test Category']);
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
    public function it_should_provide_the_category_given_a_parent_and_a_keyword() {
        $this->generateData();

        $actual = Category::getChildWithKeyword($this->generatedCategories['categoryA'], 'Category_AA');
        self::assertSame('Category AA', $actual->name);
    }

    /** @test */
    public function it_should_provide_the_category_given_no_parent_and_a_keyword() {
        $this->generateData();

        $actual = Category::getChildWithKeyword(null, 'Category_A');
        self::assertSame('Category A', $actual->name);
    }

    /** @test */
    public function it_should_trim_names() {
        $category = new Category(['name' => "   ABC   \n"]);
        self::assertSame('ABC', $category->name);
    }

    /**
     * Creates some data used when testing fetched results.
     */
    private function generateData() {
        $this->generatedCategories['categoryA'] = Category::create(['name' => 'Category A']);

        $this->generatedCategories['categoryAA'] = new Category(['name' => 'Category AA']);
        $this->generatedCategories['categoryAA']->parent()->associate($this->generatedCategories['categoryA']);
        $this->generatedCategories['categoryAA']->save();

        $this->generatedCategories['categoryAB'] = new Category(['name' => 'Category AB']);
        $this->generatedCategories['categoryAB']->parent()->associate($this->generatedCategories['categoryA']);
        $this->generatedCategories['categoryAB']->save();

        $this->generatedCategories['categoryB'] = Category::create(['name' => 'Category B']);
        $this->generatedCategories['categoryBA'] = new Category(['name' => 'Category BA']);
        $this->generatedCategories['categoryBA']->parent()->associate($this->generatedCategories['categoryB']);
        $this->generatedCategories['categoryBA']->save();
    }
}
