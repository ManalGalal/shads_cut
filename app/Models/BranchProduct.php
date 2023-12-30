<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchProduct extends Model {
    use HasFactory;
    protected $fillable = ["branch_id", "product_id", "quantity", "commission", "min_quantity", "max_quantity"];


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
