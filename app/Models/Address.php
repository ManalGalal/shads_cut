<?php

namespace App\Models;

use App\Traits\HasLocation;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model {
    use HasFactory, HasLocation, Localizable;
    protected $fillable = [
        "user_id", "region_id", "location_id",
        "name", "home", "street", "building",
        "floor", "appartment", "comment"
    ];
    protected $localizable = [];
    public function region() {
        return $this->belongsTo(Region::class, "region_id");
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
