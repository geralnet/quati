<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductImage
 *
 * @mixin Builder
 * @property int       id
 * @property BelongsTo product
 */
class ProductImage extends Pathable {
    public function file() {
        return $this->belongsTo(UploadedFile::class);
    }

    function getId() : int {
        return $this->id;
    }

    function getPathname() : string {
        return $this->file->logical_path;
    }

    public function getProduct() {
        return $this->path->parent->component;
    }
}

