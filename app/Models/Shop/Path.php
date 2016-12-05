<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Path
 *
 * @mixin Builder
 * @property string   pathname
 * @property Pathable component
 */
class Path extends EntityRelationshipModel {
    public static function createForComponent(Pathable $component) {
        $attributes['pathname'] = $component->getPathname();
        $attributes['component_id'] = $component->getId();
        $attributes['component_type'] = get_class($component);
        return self::forceCreate($attributes);
    }

    public static function getRoot() : Path {
        return Path::where('parent_id', null)->firstOrFail();
    }

    protected $fillable = ['pathname'];

    protected $table = 'shop_pathtree';

    public function component() {
        return $this->morphTo();
    }
}
