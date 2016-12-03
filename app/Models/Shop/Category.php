<?php
declare(strict_types = 1);

namespace App\Models\Shop;

use App\Models\EntityRelationshipModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @mixin Builder
 * @property int                   id
 * @property int                   parent_id
 * @property string                name
 * @property string                keyword
 * @property string                description
 * @property Category              parent
 * @property Collection|Product[]  products
 * @property Collection|Category[] subcategories
 */
class Category extends EntityRelationshipModel {
    /** @var int|null Cached id of root category. */
    private static $cachedRootId = null;

    public static function createInRoot(array $attributes) {
        return self::createSubcategory(self::getRoot(), $attributes);
    }

    public static function createSubcategory(Category $parent, array $attributes) {
        $category = new Category($attributes);
        $category->parent()->associate($parent);
        $category->save();
        return $category;
    }

    public static function getRoot() : Category {
        return static::find(self::getRootId())->firstOrFail();
    }

    public static function getRootId() {
        if (is_null(self::$cachedRootId)) {
            $root = static::select('id')
                          ->where('parent_id', null)->first();
            self::$cachedRootId = $root->id;
        }
        return self::$cachedRootId;
    }

    /** @var array */
    protected $attributes = [
        'description' => '',
    ];

    /** @var string[] */
    protected $fillable = ['name', 'keyword', 'description'];

    /** @var string */
    private $keywordPath = null;

    public function getKeywordPath() {
        if (is_null($this->keywordPath)) {
            if ($this->isRoot()) {
                return '/';
            }

            $path = $this->parent()->getResults()->getKeywordPath();

            if ($path == '/') {
                $path = '';
            }

            $this->keywordPath = $path.'/'.$this->keyword;
        }
        return $this->keywordPath;
    }

    /**
     * @return bool
     */
    public function hasProducts() {
        return ($this->products()->count() > 0);
    }

    /**
     * @return bool
     */
    public function hasSubcategories() {
        return ($this->subcategories()->count() > 0);
    }

    /**
     * @return bool
     */
    public function isRoot() {
        return is_null($this->parent_id);
    }

    /**
     * @return BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function products() {
        return $this->hasMany(Product::class);
    }

    /**
     * @param string $name
     */
    public function setNameAttribute(string $name) {
        $name = trim($name);
        $this->attributes['name'] = $name;

        if (!isset($this->attributes['keyword'])) {
            $keyword = KeywordGenerator::fromName($name);
            $this->attributes['keyword'] = $keyword;
        }
    }

    /**
     * @return Collection
     */
    public function subcategories() {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
