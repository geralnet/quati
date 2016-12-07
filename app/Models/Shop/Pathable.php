<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;

/**
 * Interface Pathable
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
}
