<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
    /** @var string[] */
    protected $fillable = ['name'];

    /** @var string */
    protected $name;

    /**
     * @return Collection
     */
    public static function getRootCategories() {
        return static::whereNull('parent_id')->get();
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
}
