<?php

/**
 * Class TestCase
 */
abstract class TestCase extends Illuminate\Foundation\Testing\TestCase {
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl;

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

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
