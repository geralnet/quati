<?php

use app\Models\Shop\KeywordGenerator;
use Tests\TestCase;

/**
 * Class KeywordGeneratorTest
 */
class KeywordGeneratorTest extends TestCase {
    /** @test */
    public function it_removes_accents() {
        self::assertSame('Historia_da_Computacao-Regua_de_Calculo',
            KeywordGenerator::fromName('História da Computação/Régua de Cálculo'));
    }

    /** @test */
    public function it_replaces_invalid_characters_with_dashes() {
        self::assertSame('Super-Category_123_F--k', KeywordGenerator::fromName('Super-Category 123 F#$k'));
    }

    /** @test */
    public function it_replaces_spaces_with_underscores() {
        self::assertSame('Category_A', KeywordGenerator::fromName('Category A'));
    }
}
