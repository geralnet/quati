<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Psy\Exception\RuntimeException;

/**
 * Interface Pathable
 *
 * @property Path path
 */
abstract class Pathable extends EntityRelationshipModel {
    /**
     * @return int
     */
    public abstract function getId() : int;

    /**
     * @return string
     */
    public abstract function getPathname() : string;

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
