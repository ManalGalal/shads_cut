<?php

namespace App\Traits;


trait HasProfilePicture {
    public function getProfilePictureAttribute($value) {
        if (!$value) {
            return "";
        }
        if (strpos($value, "googleusercontent.com") || strpos($value, "graph.facebook.com")) {
            return $value;
        }
        return env("APP_URL", "https://shadscut.com/") . $value;
    }
}
