<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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

    public static function createFromExternalFile($attributes, $file) {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        $sha1 = sha1_file($file);
        $shaPath = self::create_sha1_path($sha1);

        return DB::transaction(
            function() use ($file, $attributes, $shaPath) {
                if (is_string($attributes)) {
                    $attributes = ['logical_path' => $attributes];
                }
                $attributes['real_path'] = $shaPath;
                $attributes['logical_path'] = '';
                $uploadedFile = UploadedFile::forceCreate($attributes);
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

    public function owner() : Relation {
        return $this->morphTo();
    }
}
