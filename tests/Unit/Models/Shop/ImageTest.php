<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Image;
use App\Models\Shop\Path;
use App\Models\Shop\Pathable;
use App\UploadedFile;
use Tests\Unit\TestCase;

/**
 * @property string filename
 */
class ImageTest extends TestCase {
    public static function createWithPath(array $attributes = [], Pathable $parent = null, $sourceFile = null) {
        $image = PathTest::createWithPath(Image::class, $attributes, $parent);
        $fileAttributes = [
            'logical_path' => '',
            'owner_type'   => Image::class,
            'owner_id'     => $image->id,
        ];
        if (is_null($sourceFile)) {
            $fileAttributes['real_path'] = 'something.png';
            UploadedFile::forceCreate($fileAttributes);
        }
        else {
            UploadedFile::createFromExternalFile($fileAttributes, $sourceFile);
        }
        $ups = UploadedFile::all()->toArray();
        $img = Image::all()->toArray();

        return $image;
    }

    /** @test */
    public function it_exists() {
        $image = self::createWithPath();
        self::assertNotNull($image);
    }

    /** @test */
    public function it_has_a_filename() {
        $image = self::createWithPath(['filename' => 'file.jpg']);
        self::assertSame('file.jpg', $image->filename);
    }

    /** @test */
    public function it_has_a_path() {
        $image = self::createWithPath();
        self::assertInstanceOf(Path::class, $image->path);
    }

    /** @test */
    public function it_has_an_uploaded_file() {
        $image = self::createWithPath();
        $file = $image->file;

        self::assertInstanceOf(UploadedFile::class, $image->file);
        self::assertSame($file->id, $image->file->id);
    }

    /** @test */
    public function it_is_pathable() {
        $image = self::createWithPath();
        self::assertInstanceOf(Pathable::class, $image);
    }
}
