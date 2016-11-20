<?php

use App\Http\Controllers\HomeController;
use App\Models\Product\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Tests\TestCase;

class HomeControllerTest extends TestCase {
    /** @test */
    public function it_should_not_have_an_error_for_homepage() {
        $this->visit('/')->assertResponseOk();
    }

    /** @test */
    public function it_handles_the_home_page() {
        $homeController = Mockery::mock(HomeController::class)->makePartial();
        $homeController->shouldReceive('index')->once();
        App::instance(HomeController::class, $homeController);

        $this->visit('/');
    }

    /** @test */
    public function it_must_provide_the_home_view() {
        /** @var Response $response */
        $response = $this->action('GET', 'HomeController@index');
        /** @var View $view */
        $view = $response->getOriginalContent();
        self::assertSame('home', $view->getName());
    }

    /** @test */
    public function it_must_provide_the_categories_parameter_to_the_view() {
        /** @var Response $response */
        $response = $this->action('GET', 'HomeController@index');
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

        $viewData = $this->action('GET', 'HomeController@index')->getOriginalContent()->getData();

        $expected = ['Category A', 'Category B'];
        $actual = [];
        foreach ($viewData['root_categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }
}
