<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

/**
 * Class TestCase
 */
abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase {
    use DatabaseTransactions;

    /**
     * TestCase constructor.
     */
    public function __construct() {
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
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication() {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
