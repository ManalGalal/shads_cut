<?php

namespace App\Models;

use App\Traits\BranchRelationships;
use App\Traits\HasLocation;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model {
    use HasFactory, HasLocation, Localizable, BranchRelationships;

    protected $fillable = [
        "name_en", "name_ar", "address_en", "address_ar",
        "info_en", "info_ar", "location_id", "is_van",
        "home"
    ];
    protected $localizable = ["name", "address", "info"];


}
