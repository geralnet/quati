<?php

namespace App\EntityRelationshipModels\Shop;

use App\EntityRelationshipModels\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends EntityRelationshipModel {
    /** @var string[] */
    protected $fillable = ['name'];

    /** @var string */
    protected $name;

    /**
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
