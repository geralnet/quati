<?php

use App\UploadedFile;
use Tests\TestCase;

/**
 * Class FileTest
 */
class UploadedFileTest extends TestCase {
    /** @test */
    public function it_can_save_an_external_file() {
        $source = tempnam(sys_get_temp_dir(), 'UploadFileSystemTest_');
        file_put_contents($source, 'This is a file!');
        $fs = new UploadedFile();
        $fs->createFromExternalFile('/afile.txt', $source);

        $sha1 = sha1('This is a file!');
        $path = UploadedFile::create_sha1_path($sha1);
        self::assertFileExists(__DIR__.'/../../../storage/app/tests/uploads/'.$path);
    }

    /** @test */
    public function it_encodes_a_sha1_into_a_path() {
        $sha = sha1('a sha');
        $path = UploadedFile::create_sha1_path($sha);
        self::assertSame('17/42be/873a1d86d36025f9680547d25452e75bf5', $path);
    }

    /** @test */
    public function it_encodes_another_sha1_into_a_path() {
        $sha1 = sha1('another sha');
        $path = UploadedFile::create_sha1_path($sha1);
        self::assertSame('a2/812c/dd9296174feedc5d174905196998211723', $path);
    }

    /** @test */
    public function it_has_a_logical_logical_path() {
        $file = UploadedFile::create([
            'logical_path' => '/logical/path',
            'real_path'    => '',
        ]);
        self::assertSame('/logical/path', $file->logical_path);
    }

    /** @test */
    public function it_has_a_realpath() {
        $file = UploadedFile::create([
            'real_path'    => '/real/path',
            'logical_path' => '',
        ]);
        self::assertSame('/real/path', $file->real_path);
    }

    /** @test */
    public function it_can_generate_a_filepath_based_on_a_sha1_hash() {
        $sha1 = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $expected = 'da/39a3/ee5e6b4b0d3255bfef95601890afd80709';
        $actual = UploadedFile::create_sha1_path($sha1);
        self::assertSame($expected, $actual);
    }
}
