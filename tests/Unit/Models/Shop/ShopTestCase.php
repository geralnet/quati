<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Category;
use Tests\Unit\TestCase;

/**
 * Class TestCase
 */
abstract class ShopTestCase extends TestCase {
    /**
     * @param Category[] $categories
     * @return Category Root category.
     */
    protected function setCategoryHierarchy(array $categories) : Category {
        if (count($categories) == 0) {
            return null;
        }
        $root = array_shift($categories);
        $last = $root;
        while (!is_null($next = array_shift($categories))) {
            $next->parent()->associate($last);
            $next->save();
            $last = $next;
        }
        return $root;
    }
}
