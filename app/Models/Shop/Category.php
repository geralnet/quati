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
 * @property int      id
 * @property string   name
 * @property string   keyword
 * @property Category parent
 */
class Category extends EntityRelationshipModel {
    /** Special keyword for the root category */
    const KEYWORD_ROOT = '[root]';

    /** @var Category Caches the root category. */
    private static $rootCategory = null;

    public static function createInRoot(array $attributes) {
        return self::createSubcategory(self::getRoot(), $attributes);
    }

    public static function createSubcategory(Category $parent, array $attributes) {
        $category = new Category($attributes);
        $category->parent()->associate($parent);
        $category->save();
        return $category;
    }

    public static function getRoot() : Category {
        if (is_null(self::$rootCategory)) {
            self::$rootCategory = static::where('keyword', Category::KEYWORD_ROOT)->firstOrFail();
        }
        return self::$rootCategory;
    }

    /** @var string[] */
    protected $fillable = ['name', 'keyword'];

    /** @var string */
    private $keywordPath = null;

    public function getKeywordPath() {
        if (is_null($this->keywordPath)) {
            if ($this->keyword == self::KEYWORD_ROOT) {
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
        return $this->hasMany(Category::class, 'parent_id')->getQuery()->where('keyword', '!=', '[root]')->get();
    }
}
