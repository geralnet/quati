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
    public function the_homepage_is_handled_by_homecontroller() {
        $homeController = Mockery::mock(HomeController::class)->makePartial();
        $homeController->shouldReceive('index')->once();
        App::instance(HomeController::class, $homeController);

        $this->visit('/');
    }

    /** @test */
    public function the_homepage_view_must_be_home() {
        /** @var Response $response */
        $response = $this->action('GET', 'HomeController@index');
        /** @var View $view */
        $view = $response->getOriginalContent();
        self::assertSame('home', $view->getName());
    }

    /** @test */
    public function the_homepage_view_must_have_the_categories_parameter() {
        /** @var Response $response */
        $response = $this->action('GET', 'HomeController@index');
        /** @var View $view */
        $view = $response->getOriginalContent();
        $data = $view->getData();
        self::assertArrayHasKey('categories', $data);
    }
    
    /** @test */
    public function the_homepage_view_must_receive_the_root_categories() {
        $categoryA = Category::create(['name' => 'Category A']);

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        Category::create(['name' => 'Category B']);

        $viewData = $this->action('GET', 'HomeController@index')->getOriginalContent()->getData();

        $expected = [ 'Category A', 'Category B'];
        $actual = [];
        foreach ($viewData['categories'] as $category) {
            $actual[] = $category->name;
        }

        self::assertSame($expected, $actual);
    }
}
