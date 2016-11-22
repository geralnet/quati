<?php

use App\Http\Controllers\ProductBrowserController;
use App\Models\Product\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Tests\TestCase;

class ProductBrowserControllerTest extends TestCase {
    /** @test */
    public function it_should_not_have_an_error_for_homepage() {
        $this->visit('/')->assertResponseOk();
    }

    /** @test */
    public function it_handles_the_home_page() {
        $controller = Mockery::mock(ProductBrowserController::class)->makePartial();
        $controller->shouldReceive('index')->once();
        App::instance(ProductBrowserController::class, $controller);

        $this->visit('/');
    }

    /** @test */
    public function it_must_provide_the_home_view() {
        /** @var Response $response */
        $response = $this->visit('/')->response;
        /** @var View $view */
        $view = $response->getOriginalContent();
        self::assertSame('home', $view->getName());
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
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        Category::create(['name' => 'Category B']);

        $viewData = $this->visit('/')->response->getOriginalContent()->getData();

        $expected = ['Category A', 'Category B'];
        $actual = [];
        foreach ($viewData['root_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }

    /** @test */
    public function it_handles_a_category_view() {
        Category::create(['name' => 'Category Name', 'keyword' => 'TheKeyword']);

        $controller = Mockery::mock(ProductBrowserController::class)->makePartial();
        $controller->shouldReceive('index')->once();
        App::instance(ProductBrowserController::class, $controller);

        $this->visit('/TheKeyword');
    }

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category() {
        Category::create(['name' => 'Category Name', 'keyword' => 'TheKeyword']);

        $response = $this->visit('/TheKeyword')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Name', $viewData['current_category']->name);
    }

    /** @test */
    public function it_handles_a_category_view_providing_the_current_category_for_a_longer_path() {
        $categoryAlpha = Category::create(['name' => 'Category Alpha', 'keyword' => 'Alpha']);
        $categoryBeta = new Category(['name' => 'Category Beta', 'keyword' => 'Beta']);
        $categoryBeta->parent()->associate($categoryAlpha);
        $categoryBeta->save();

        $response = $this->visit('/Alpha/Beta')->response;
        $viewData = $response->getOriginalContent()->getData();

        self::assertSame('Category Beta', $viewData['current_category']->name);
    }

    /** @test */
    public function it_returns_404_for_an_invalid_path() {
        $this->get('/@InvalidPath')->assertResponseStatus(404);
    }

    /** @test */
    public function it_provides_the_root_categories_as_shown_categories_if_homepage() {
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        Category::create(['name' => 'Category B']);

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
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        Category::create(['name' => 'Category B']);

        $viewData = $this->visit('/Category_A')->response->getOriginalContent()->getData();

        $expected = ['Category AA'];
        $actual = [];
        foreach ($viewData['show_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }
}
