<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
    /** @var array */
    protected $attributes = [
        'description' => '',
    ];

    /** @var string[] */
    protected $fillable = ['name', 'description', 'price'];

    public function getCategory() {
        return $this->path->parent->component;
    }

    public function getId() : int {
        return $this->id;
    }

    /**
     * @param int $index Image index (first image is 0).
     * @return Product|null
     */
    public function getImage(int $index) {
        $images = $this->getImages();
        if ($index >= count($images)) {
            return null;
        }

        return $images[$index];
    }

    /**
     * @param int $number Image number (first image is 1).
     * @return string
     */
    public function getImageUrl(int $number) : string {
        $image = $this->getImage($number - 1);
        if (is_null($image)) {
            return '#';
        }
        return $image->getUrl();
    }

    /**
     * @return Collection
     */
    public function getImages() : Collection {
        $ids = [];
        $subpaths = $this->path->subpaths()
                               ->where('component_type', ProductImage::class)
                               ->get(['component_id']);
        foreach ($subpaths as $subpath) {
            $ids[] = $subpath['component_id'];
        }
        return ProductImage::findMany($ids);
    }

    function getPathname() : string {
        return Pathable::makePathname($this->name);
    }
}
