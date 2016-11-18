<?php

use Tests\TestCase;

class HomeControllerTest extends TestCase {
    /** @test */
    public function it_should_not_have_an_error_for_homepage() {
        $this->visit('/')->assertResponseOk();
    }
}
