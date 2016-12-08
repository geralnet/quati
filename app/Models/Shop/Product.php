<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Product
 *
 * @mixin Builder
 * @property int      id
 * @property string   name
 * @property string   keyword
 * @property int      price
 * @property Category category
 */
class Product extends Pathable {
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
    protected $fillable = ['name', 'description', 'price'];

    /**
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    function getId() : int {
        return $this->id;
    }

    public function getImageURL($number) {
        $number--; // Image index starts from 0.
        $count = $this->images()->count();
        if ($number >= $count) {
            return '#';
        }

        $image = $this->images[$number];
        $file = $image->file;
        $path = $file->logical_path;
        $url = preg_replace('#^/images/#', '/@images/', $path);
        return $url;
    }

    function getPathname() : string {
        return Pathable::makePathname($this->name);
    }

    /**
     * @return HasMany
     */
    public function images() {
        return $this->hasMany(ProductImage::class);
    }
}
