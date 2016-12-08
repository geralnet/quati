<?php

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Path
 *
 * @mixin Builder
 * @property int      id
 * @property string   fullpath
 * @property string   pathname
 * @property Path     parent
 * @property Pathable component
 */
class Path extends EntityRelationshipModel {
    public static function boot() {
        parent::boot();
        Path::saving(function(Path $path) {
            return $path->updateFullpath();
        });
    }

    public static function createForComponent(Pathable $component, Path $parent = null) : Path {
        $parent = $parent ?: self::getRoot();
        $attributes = [
            'pathname'       => $component->getPathname(),
            'component_id'   => $component->getId(),
            'component_type' => get_class($component),
            'parent_id'      => $parent->id,
        ];
        return self::forceCreate($attributes);
    }

    public static function getRoot() : Path {
        return Path::query()
                   ->where('parent_id', null)
                   ->where('fullpath', '/')
                   ->firstOrFail(); // TODO add ->get();
    }

    protected $fillable = ['pathname'];

    protected $table = 'shop_pathtree';

    public function component() : MorphTo {
        return $this->morphTo();
    }

    public function parent() : BelongsTo {
        return $this->belongsTo(Path::class);
    }

    public function subpaths() : HasMany {
        return $this->hasMany(Path::class, 'parent_id');
    }

    protected function updateFullpath() : bool {
        $before = $this->fullpath;
        if (is_null($this->parent)) {
            $this->fullpath = '/';
        }
        else {
            $fullpath = $this->parent->fullpath;
            $this->fullpath = $fullpath.($fullpath == '/' ? '' : '/').$this->pathname;
        }
        if ($before !== $this->fullpath) {
            foreach ($this->subpaths()->get() as $subpath) {
                if (!$subpath->updateFullpath()) {
                    return false;
                }
            }
        }
        return true;
    }
}
