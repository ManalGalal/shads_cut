<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, Localizable;
    protected $fillable = ["name_en", "name_ar"];
    protected $localizable = ["name"];
    public function regions() {
        return $this->hasMany(Region::class, "city_id");
    }
}
