<?php
declare(strict_types = 1);

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ShopController;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Tests\Unit\Models\Shop\CategoryTest;
use Tests\Unit\TestCase;

class ShopControllerTest extends TestCase {
    /** @var Category[] */
    private $generatedCategories = null;

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category() {
        CategoryTest::createInRoot(['name' => 'Category Name', 'keyword' => 'TheKeyword']);

        $response = $this->visit('/TheKeyword')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Name', $viewData['category']->name);
    }

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category_for_a_longer_path() {
        $categoryAlpha = CategoryTest::createInRoot(['name' => 'Category Alpha', 'keyword' => 'Alpha']);
        CategoryTest::createSubcategory($categoryAlpha, ['name' => 'Category Beta', 'keyword' => 'Beta']);

        $response = $this->visit('/Alpha/Beta')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Beta', $viewData['category']->name);
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
        $categoryA = CategoryTest::createInRoot(['name' => 'Category A']);
        $categoryAA = CategoryTest::createSubcategory($categoryA, ['name' => 'Category AA']);
        $categoryAB = CategoryTest::createSubcategory($categoryA, ['name' => 'Category AB']);

        $categoryB = CategoryTest::createInRoot(['name' => 'Category B']);
        $categoryBA = CategoryTest::createSubcategory($categoryB, ['name' => 'Category BA']);

        $productA1 = Product::createInCategory($categoryA, ['name' => 'Product A1', 'price' => 10]);

        $this->generatedCategories = compact(
            'categoryA', 'categoryAA', 'categoryAB', 'categoryB', 'categoryBA', 'productA1'
        );
    }
}
