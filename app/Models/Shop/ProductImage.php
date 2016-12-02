<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductImage
 *
 * @mixin Builder
 * @property belongsTo product
 */
class ProductImage extends EntityRelationshipModel {
    public static function createForProduct($product, $string) {
        $image = new ProductImage();
        $image->product()->associate($product);
        $image->save();
        return $image;
    }

    public function file() {
        return $this->belongsTo(UploadedFile::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}

