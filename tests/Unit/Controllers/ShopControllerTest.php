<?php
declare(strict_types = 1);

namespace Tests\Unit\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Tests\Unit\Models\Shop\PathTest;
use Tests\Unit\TestCase;

class ShopControllerTest extends TestCase {
    /** @var Path[] */
    private $generatedPaths = null;

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category() {
        PathTest::createForCategory(['name' => 'Category Name', 'keyword' => 'TheKeyword']);

        $response = $this->visit('/TheKeyword')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Name', $viewData['category']->name);
    }

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category_for_a_longer_path() {
        $alpha = PathTest::createForCategory(['name' => 'Category Alpha', 'keyword' => 'Alpha']);
        PathTest::createForCategory(['name' => 'Category Beta', 'keyword' => 'Beta'], $alpha);

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

    /**
     * Creates some data used when testing fetched results.
     */
    private function generateData() {
        $pathA = PathTest::createForCategory(['name' => 'Category A']);
        $pathAA = PathTest::createForCategory(['name' => 'Category AA'], $pathA);
        $pathAB = PathTest::createForCategory(['name' => 'Category AB'], $pathA);

        $pathB = PathTest::createForCategory(['name' => 'Category B']);
        $pathBA = PathTest::createForCategory(['name' => 'Category BA'], $pathB);

        $pathA1 = PathTest::createForProduct(['name' => 'Product A1', 'price' => 10], $pathA);

        $this->generatedPaths = compact(
            'pathA', 'pathAA', 'pathAB', 'pathB', 'pathBA', 'pathA1'
        );
    }
}
