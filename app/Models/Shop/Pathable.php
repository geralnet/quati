<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Psy\Exception\RuntimeException;

/**
 * Interface Pathable
 *
 * @property Path path
 */
abstract class Pathable extends EntityRelationshipModel {
    /**
     * @param $name
     * @return string
     */
    public static function makePathname($name) : string {
        $pathname = str_replace(' ', '_', $name);
        $pathname = iconv('UTF-8', 'ASCII//TRANSLIT', $pathname);
        $pathname = preg_replace('/[^A-Za-z0-9_]/u', '-', $pathname);
        return $pathname;
    }

    /**
     * @return int
     */
    public abstract function getId() : int;

    /**
     * @return string
     */
    public abstract function getPathname() : string;

    /**
     * @return MorphOne
     */
    public function path() : MorphOne {
        return $this->morphOne(Path::class, 'component');
    }

    /**
     * @return string
     */
    public function getUrl() : string {
        $path = $this->path;
        if (is_null($path)) {
            throw new RuntimeException('Path not available, cannot create URL.');
        }
        return $path->fullpath;
    }
}
