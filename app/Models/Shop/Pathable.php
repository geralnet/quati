<?php
declare(strict_types = 1);

namespace App\Models\Shop;

/**
 * Interface Pathable
 */
interface Pathable {
    /**
     * @return int
     */
    function getId() : int;

    /**
     * @return string
     */
    function getPathname() : string;
}
