<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Path;
use RuntimeException;
use Tests\Unit\TestCase;

/**
 * Class PathableTest
 */
class PathableTest extends TestCase {
    /** @test */
    public function it_provides_a_url() {
        $pathable = CategoryTest::createInRoot(['name' => 'Category URL']);
        Path::createForComponent($pathable);

        self::assertSame('/Category_URL', $pathable->getUrl());
    }

    /** @test */
    public function it_throws_an_exception_if_a_pathable_without_a_path_tries_to_get_the_url() {
        $pathable = CategoryTest::createInRoot(['name' => 'Category URL']);
        $this->expectException(RuntimeException::class);
        $pathable->getUrl();
    }
}
