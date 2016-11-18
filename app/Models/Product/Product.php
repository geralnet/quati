<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 */
class Product extends Model {
    /** @var string[] */
    protected $fillable = ['name'];

    /** @var string */
    protected $name;

    /** @var Category */
    protected $category;
}
