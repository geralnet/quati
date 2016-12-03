<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @mixin Builder
 * @property int                  id
 * @property int                  parent_id
 * @property string               name
 * @property string               keyword
 * @property string               description
 * @property Category             parent
 * @property Collection|Product[] products
 */
class Category extends EntityRelationshipModel {
    /** @var Category Caches the root category. */
    private static $rootCategory = null;

    public static function createInRoot(array $attributes) {
        return self::createSubcategory(self::getRoot(), $attributes);
    }

    public static function createSubcategory(Category $parent, array $attributes) {
        $attributes['parent'] = $parent;
        $category = new Category($attributes);
        $category->parent()->associate($parent);
        $category->save();
        return $category;
    }

    public static function getRoot() : Category {
        if (is_null(self::$rootCategory)) {
            self::$rootCategory = static::where('parent_id', null)->firstOrFail();
        }
        return self::$rootCategory;
    }

    /** @var array */
    protected $attributes = [
        'description' => '',
    ];

    /** @var string[] */
    protected $fillable = ['name', 'keyword', 'description'];

    /** @var string */
    private $keywordPath = null;

    public function getKeywordPath() {
        if (is_null($this->keywordPath)) {
            if ($this->isRoot()) {
                return '/';
            }

            $path = $this->parent()->getResults()->getKeywordPath();

            if ($path == '/') {
                $path = '';
            }

            $this->keywordPath = $path.'/'.$this->keyword;
        }
        return $this->keywordPath;
    }

    public function hasProducts() {
        return ($this->products()->count() > 0);
    }

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
    public function products() {
        return $this->hasMany(Product::class);
    }

    /**
     * @param $name
     */
    public function setNameAttribute($name) {
        $name = trim($name);
        $this->attributes['name'] = $name;

        if (!isset($this->attributes['keyword'])) {
            $keyword = KeywordGenerator::fromName($name);
            $this->attributes['keyword'] = $keyword;
        }
    }

    /**
     * @return Collection
     */
    public function subcategories() {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->hasMany(Category::class, 'parent_id')->get();
    }
}
