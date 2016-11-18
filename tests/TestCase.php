<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Class TestCase
 */
abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase {
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * TestCase constructor.
     */
    public function __construct() {
        $this->baseUrl = env('APP_URL');
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
