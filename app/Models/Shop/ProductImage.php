<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductImage
 *
 * @mixin Builder
 * @property BelongsTo product
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

