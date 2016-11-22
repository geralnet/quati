<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model {
    /** @var string[] */
    protected $fillable = ['name', 'keyword'];

    /** @var string */
    protected $name;

    /**
     * @return Collection
     */
    public static function getRootCategories() {
        return static::whereNull('parent_id')->get();
    }

    public static function getChildWithKeyword($parent, $keyword) {
        $parentid = is_null($parent) ? null : $parent->id;
        return Category::where('parent_id', $parentid)->where('keyword', $keyword)->first();
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

    /**
     * @return HasMany
     */
    public function products() {
        return $this->hasMany(Product::class);
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
    public function subcategories() {
        return $this->hasMany(Category::class, 'parent_id');
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
}
