<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class UploadedFile
 *
 * @property mixed logical_path
 * @property mixed real_path
 */
class UploadedFile extends Model {
    const DRIVE_NAME = 'uploads';

    public static function createFromExternalFile($logicalPath, $file) {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        $sha1 = sha1_file($file);
        $shaPath = self::create_sha1_path($sha1);

        return DB::transaction(
            function() use ($file, $logicalPath, $shaPath) {
                $uploadedFile = UploadedFile::create([
                    'real_path'    => $shaPath,
                    'logical_path' => $logicalPath,
                ]);
                if (!Storage::drive(self::DRIVE_NAME)->has($shaPath)) {
                    $resource = fopen($file, 'r');
                    Storage::drive(self::DRIVE_NAME)
                           ->put($shaPath, $resource);
                    fclose($resource);
                }
                return $uploadedFile;
            }
        );
    }

    public static function create_sha1_path($sha) {
        return substr($sha, 0, 2)
            .'/'.substr($sha, 2, 4)
            .'/'.substr($sha, 6);
    }

    protected $fillable = ['real_path', 'logical_path'];
}
