<?php

namespace App\Models\Shop;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Image
 *
 * @property int          id
 * @property string       filename
 * @property UploadedFile file
 */
class Image extends Pathable {
    protected $fillable = ['filename'];

    public function file() : Relation {
        return $this->morphOne(UploadedFile::class, 'owner');
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPathname() : string {
        return $this->filename;
    }
}
