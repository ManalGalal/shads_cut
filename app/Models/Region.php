<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model {
    use HasFactory, Localizable;
    protected $fillable = ["name_en", "name_ar", "city_id"];
    protected $localizable = ["name"];
    protected $hidden = ["pivot"];
    public function city() {
        return $this->belongsTo(City::class, "city_id")->select(["id", "name_en", "name_ar"]);
    }
    public function branches() {
        return $this->belongsToMany(Branch::class, "branch_regions");
    }
}
