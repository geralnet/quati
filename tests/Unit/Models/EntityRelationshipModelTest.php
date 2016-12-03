<?php
use App\Models\EntityRelationshipModel;
use Tests\Unit\TestCase;

class EntityRelationshipModelTest extends TestCase {
    /** @test */
    public function it_must_use_the_namespace_to_generate_the_table_name() {
        $erm = new EntityRelationshipModel();
        self::assertSame('entityrelationshipmodels', $erm->getTable());
    }
}
