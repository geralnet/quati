<?php
declare(strict_types = 1);

namespace App\Models\UploadedFile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

interface UploadedFileOwner {
    public function getId() : int;
}
