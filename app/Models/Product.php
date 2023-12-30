<?php

namespace App\Models;

use App\Traits\AddUrlToImage;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory, AddUrlToImage, Localizable;
    protected $fillable = [
        "name_en", "name_ar", "price", "info_en", "info_ar", "featured",
        "image", "category_id", "sku", "barcode", "quantity", "commission",
        "brand_id"
    ];
    protected $localizable = ["name", "info"];
    protected $hidden = ["pivot"];
    public function category() {
        return $this->belongsTo(Category::class, "category_id");
    }
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    public function branches() {
        return $this->belongsToMany(Branch::class, "branch_products");
    }
}
