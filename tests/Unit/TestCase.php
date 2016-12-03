<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;
use Mockery;

/**
 * Class TestCase
 */
abstract class TestCase extends IlluminateTestCase {
    use DatabaseTransactions;

    /**
     * TestCase constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->baseUrl = env('APP_URL');
    }

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl;

    /** @after */
    public function close_mockery() {
        Mockery::close();
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication() {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
