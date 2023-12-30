<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory, Localizable;

    protected $fillable = ["name_en", "name_ar", "sort_order"];
    protected $localizable = ["name"];
    public function services() {
        return $this->hasMany(Service::class, "category_id");
    }
    public function products() {
        return $this->hasMany(Product::class, "category_id");
    }
    public function scopeOrderBySortOrder($query) {
        return $query->orderBy('sort_order');
    }
}
