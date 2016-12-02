<?php

use App\UploadedFile;
use Tests\TestCase;

class FileControllerTest extends TestCase {
    /** @test */
    public function it_should_return_the_requested_image_file() {
        $filename = __DIR__.'/../Fixtures/image.png';
        UploadedFile::createFromExternalFile('/images/image.png', $filename);

        /** @var Response $response */
        $response = $this->visit('/@images/image.png')->response;
        /** @var View $view */
        $data = $response->getContent();
        self::assertSame(file_get_contents($filename), $data);
    }
}
