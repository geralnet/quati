<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model {
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

    public static function getChildWithKeyword($parent, $keyword) {
        $parentid = is_null($parent) ? Category::$rootCategory->id : $parent->id;
        return Category::where('parent_id', $parentid)->where('keyword', $keyword)->first();
    }

    public static function getRoot() : Category {
        if (is_null(self::$rootCategory)) {
            self::$rootCategory = static::where('keyword', Category::KEYWORD_ROOT)->firstOrFail();
        }
        return self::$rootCategory;
    }

    /**
     * @param $name
     * @return string
     */
    private static function createKeywordFromName($name) :string {
        $keyword = str_replace(' ', '_', $name);
        $keyword = iconv('UTF-8', 'ASCII//TRANSLIT', $keyword);
        $keyword = preg_replace('/[^A-Za-z0-9_]/u', '-', $keyword);
        return $keyword;
    }

    /** @var string[] */
    protected $fillable = ['name', 'keyword'];

    /** @var string */
    protected $name;

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
        $keyword = self::createKeywordFromName($name);
        $this->attributes['name'] = $name;
        $this->attributes['keyword'] = $keyword;
    }

    /**
     * @return HasMany
     */
    public function subcategories() {
        return $this->hasMany(Category::class, 'parent_id')->where('keyword', '!=', '[root]');
    }
}
