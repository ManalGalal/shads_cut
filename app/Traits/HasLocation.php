<?php

namespace App\Traits;

use App\Models\Location;


trait HasLocation {
    public function location() {
        return $this->belongsTo(Location::class, "location_id")
            ->select(["lat", "long","id"]);
    }
}
