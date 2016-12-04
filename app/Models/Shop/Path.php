<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;

/**
 * Class Path
 *
 * @property string   pathname
 * @property Pathable component
 */
class Path extends EntityRelationshipModel {
    protected $fillable = ['pathname'];

    protected $table = 'shop_pathtree';

    public function component() {
        return $this->morphTo();
    }
}
