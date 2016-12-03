<?php
declare(strict_types = 1);

namespace Tests\Unit\Controllers;

use App\UploadedFile;
use Illuminate\Http\Response;
use Tests\Unit\TestCase;

class FileControllerTest extends TestCase {
    /** @test */
    public function it_should_return_the_requested_image_file() {
        $filename = __DIR__.'/../Fixtures/image.png';
        UploadedFile::createFromExternalFile('/images/image.png', $filename);

        /** @var Response $response */
        $response = $this->visit('/@images/image.png')->response;
        $data = $response->getContent();
        self::assertSame(file_get_contents($filename), $data);
    }
}
