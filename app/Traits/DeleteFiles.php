<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

Trait DeleteFiles {
    public function deleteFile($file){
        if ($file){
            $original_path = str_replace(env("APP_URL"), "" , $file);
            Storage::delete($original_path);
        }
    }
}