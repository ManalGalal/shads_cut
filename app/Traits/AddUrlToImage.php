<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait AddUrlToImage {
    public function getImageAttribute($value) {
        if (!$value) {
            return "";
        }
        // return env("APP_URL") . $value;
        return 'http://localhost:8000/api/' . $value;
    }
}
