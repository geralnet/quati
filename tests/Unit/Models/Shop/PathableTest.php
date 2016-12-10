<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Path;
use App\Models\Shop\Pathable;
use RuntimeException;
use Tests\Unit\TestCase;

/**
 * Class PathableTest
 */
class PathableTest extends TestCase {
    /** @test */
    public function it_provides_a_url() {
        $pathable = CategoryTest::createWithPath(['name' => 'Category URL']);
        Path::createForComponent($pathable);

        self::assertSame('/Category_URL', $pathable->getUrl());
    }

    /** @test */
    public function it_throws_an_exception_if_a_pathable_without_a_path_tries_to_get_the_url() {
        $pathable = CategoryTest::createWithPath(['name' => 'Category URL']);
        $this->expectException(RuntimeException::class);
        $pathable->getUrl();
    }

    /** @test */
    public function it_removes_accents() {
        self::assertSame('Historia_da_Computacao-Regua_de_Calculo',
            Pathable::makePathname('História da Computação/Régua de Cálculo'));
    }

    /** @test */
    public function it_replaces_invalid_characters_with_dashes() {
        self::assertSame('Super-Category_123_F--k',
            Pathable::makePathname('Super-Category 123 F#$k'));
    }

    /** @test */
    public function it_replaces_spaces_with_underscores() {
        self::assertSame('Category_A',
            Pathable::makePathname('Category A'));
    }
}
