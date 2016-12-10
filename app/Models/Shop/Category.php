<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

/**
 * Class Category
 *
 * @mixin Builder
 * @property int                   id
 * @property int                   parent_id
 * @property string                name
 * @property string                keyword
 * @property string                description
 * @property Category              parent
 * @property Path                  path
 * @property Collection|Product[]  products
 * @property Collection|Category[] subcategories
 */
class Category extends Pathable {
    public static function getRoot() : Category {
        $component = Path::getRoot()->component;
        if (!($component instanceof Category)) {
            throw new RuntimeException('Invalid type for root: '.get_class($component));
        }
        return $component;
    }

    /** @var array */
    protected $attributes = [
        'description' => '',
    ];

    /** @var string[] */
    protected $fillable = ['name', 'description'];

    function getId() : int {
        return $this->id;
    }

    public function getParent() {
        return $this->path->parent->component;
    }

    function getPathname() : string {
        return Pathable::makePathname($this->name);
    }

    /**
     * @return Collection
     */
    public function getProducts() : Collection {
        $ids = [];
        $subpaths = $this->path->subpaths()
                               ->where('component_type', Product::class)
                               ->get(['component_id']);
        foreach ($subpaths as $subpath) {
            $ids[] = $subpath['component_id'];
        }
        return Product::findMany($ids);
    }

    /**
     * @return Collection
     */
    public function getSubcategories() : Collection {
        $ids = [];
        $subpaths = $this->path->subpaths()
                               ->where('component_type', Category::class)
                               ->get(['component_id']);
        foreach ($subpaths as $subpath) {
            $ids[] = $subpath['component_id'];
        }
        return Category::findMany($ids);
    }

    /**
     * @return bool
     */
    public function hasProducts() {
        return ($this->getProducts()->count() > 0);
    }

    /**
     * @return bool
     */
    public function hasSubcategories() {
        return ($this->getSubcategories()->count() > 0);
    }

    /**
     * @return bool
     */
    public function isRoot() {
        return is_null($this->path->parent);
    }
}
