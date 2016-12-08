<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    protected $fillable = ['name', 'keyword', 'description'];

    function getId() : int {
        return $this->id;
    }

    function getPathname() : string {
        return Pathable::makePathname($this->name);
    }

    /**
     * @return bool
     */
    public function hasProducts() {
        return ($this->products()->count() > 0);
    }

    /**
     * @return bool
     */
    public function hasSubcategories() {
        return ($this->subcategories()->count() > 0);
    }

    /**
     * @return bool
     */
    public function isRoot() {
        return is_null($this->parent_id);
    }

    /**
     * @return BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }

    /**
     * @return Collection
     */
    public function subcategories() {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
