<?php

namespace App\Http\Controllers;

use App\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller {
    public function getImage($url) {
        $path = '/images/'.$url;
        $file = UploadedFile::where('logical_path', $path)->firstOrFail();
        $path = '/'.$file->real_path;
        $data = Storage::drive('uploads')->get($path);
        return $data;
    }
}
