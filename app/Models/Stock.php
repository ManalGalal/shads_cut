<?php

namespace App\Models;

use App\Traits\AddUrlToImage;
use App\Traits\BelongsToBranch;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {
    use HasFactory, Localizable, AddUrlToImage, BelongsToBranch;
    protected $fillable = [
        "branch_id", "name_en", "name_ar", "description_en", "description_ar",
        "stock_availability", "quantity", "image", "multi_use", "usage", "use_times",
        "left_over", "price", "sku", "min_quantity", "max_quantity"
    ];
    protected $localizable = ["name", "description"];
    protected $hidden = ["pivot"];
    public function orders() {
        return $this->belongsToMany(Order::class, "order_stocks");
    }
}
