<?php

use App\Http\Controllers\ShopController;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Tests\TestCase;

class ShopControllerTest extends TestCase {
    /** @var Category[] */
    private $generatedCategories = null;

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category() {
        Category::createInRoot(['name' => 'Category Name', 'keyword' => 'TheKeyword']);

        $response = $this->visit('/TheKeyword')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Name', $viewData['current_category']->name);
    }

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category_for_a_longer_path() {
        $categoryAlpha = Category::createInRoot(['name' => 'Category Alpha', 'keyword' => 'Alpha']);
        Category::createSubcategory($categoryAlpha, ['name' => 'Category Beta', 'keyword' => 'Beta']);

        $response = $this->visit('/Alpha/Beta')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Beta', $viewData['current_category']->name);
    }

    /** @test */
    public function it_must_provide_the_categories_parameter_to_the_view() {
        /** @var Response $response */
        $response = $this->visit('/')->response;
        /** @var View $view */
        $view = $response->getOriginalContent();
        $data = $view->getData();
        self::assertArrayHasKey('root_categories', $data);
    }

    /** @test */
    public function it_must_provide_the_root_categories_to_the_view() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        Category::createInRoot(['name' => 'Category B']);

        $viewData = $this->visit('/')->response->getOriginalContent()->getData();

        $expected = ['Category A', 'Category B'];
        $actual = [];
        foreach ($viewData['root_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }

    /** @test */
    public function it_must_provide_the_shop_category_view_for_a_category() {
        $this->generateData();
        /** @var Response $response */
        $response = $this->visit('/Category_A')->response;
        /** @var View $view */
        $view = $response->getOriginalContent();
        self::assertSame('shop.category', $view->getName());
    }

    /** @test */
    public function it_must_provide_the_shop_product_view_for_a_product() {
        $this->generateData();
        /** @var Response $response */
        $response = $this->visit('/Category_A/Product_A1')->response;
        /** @var View $view */
        $view = $response->getOriginalContent();
        self::assertSame('shop.product', $view->getName());
    }

    /** @test */
    public function it_provides_the_root_categories_as_the_shown_categories_if_homepage() {
        Category::createInRoot(['name' => 'Category A']);
        Category::createInRoot(['name' => 'Category B']);

        $viewData = $this->visit('/')->response->getOriginalContent()->getData();

        $expected = ['Category A', 'Category B'];
        $actual = [];
        foreach ($viewData['show_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }

    /** @test */
    public function it_provides_the_subcategories_as_shown_categories_if_viewing_a_category() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        Category::createInRoot(['name' => 'Category B']);

        $viewData = $this->visit('/Category_A')->response->getOriginalContent()->getData();

        $expected = ['Category AA'];
        $actual = [];
        foreach ($viewData['show_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }

    /** @test */
    public function it_returns_404_for_an_invalid_path() {
        $this->get('/InvalidPath')->assertResponseStatus(404);
    }

    /** @test */
    public function it_should_not_have_an_error_for_homepage() {
        $this->visit('/')->assertResponseOk();
    }

    /** @test */
    public function it_should_provide_the_category_given_a_parent_and_a_keyword() {
        $this->generateData();

        $actual = ShopController::getShopItemForKeyword($this->generatedCategories['categoryA'], 'Category_AA');
        self::assertSame('Category AA', $actual->name);
    }

    /** @test */
    public function it_should_provide_the_category_given_root_and_a_keyword() {
        $this->generateData();

        $actual = ShopController::getShopItemForKeyword(Category::getRoot(), 'Category_A');
        self::assertSame('Category A', $actual->name);
    }

    /** @test */
    public function it_should_provide_the_product_given_a_category_and_a_keyword() {
        $this->generateData();

        $actual = ShopController::getShopItemForKeyword($this->generatedCategories['categoryA'], 'Product_A1');
        self::assertSame('Product A1', $actual->name);
    }

    /**
     * Creates some data used when testing fetched results.
     */
    private function generateData() {
        $categoryA = Category::createInRoot(['name' => 'Category A']);
        $categoryAA = Category::createSubcategory($categoryA, ['name' => 'Category AA']);
        $categoryAB = Category::createSubcategory($categoryA, ['name' => 'Category AB']);

        $categoryB = Category::createInRoot(['name' => 'Category B']);
        $categoryBA = Category::createSubcategory($categoryB, ['name' => 'Category BA']);

        $productA1 = Product::createInCategory($categoryA, ['name' => 'Product A1', 'price' => 10]);

        $this->generatedCategories = compact(
            'categoryA', 'categoryAA', 'categoryAB', 'categoryB', 'categoryBA', 'productA1'
        );
    }
}
