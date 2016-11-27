<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 *
 * @mixin Builder
 * @property string   name
 * @property string   keyword
 * @property int      price
 * @property Category category
 */
class Product extends EntityRelationshipModel {
    public static function createInCategory(Category $category, array $attributes) {
        $product = new Product($attributes);
        $product->category()->associate($category);
        $product->save();
        return $product;
    }

    /** @var array */
    protected $attributes = [
        'description' => '',
    ];

    /** @var string[] */
    protected $fillable = ['name', 'keyword', 'description', 'price'];

    /** @var string */
    private $keywordPath = null;

    /**
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return string
     */
    public function getKeywordPath() {
        if (is_null($this->keywordPath)) {
            $this->keywordPath = $this->category->getKeywordPath().'/'.$this->keyword;
        }
        return $this->keywordPath;
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
}
