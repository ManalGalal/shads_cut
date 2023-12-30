<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use App\Traits\BelongsToWorker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additive extends Model {
    use HasFactory, BelongsToWorker, BelongsToBranch;
    protected $fillable = [
        "worker_id", "branch_id", "product_id",
        "quantity", "value", "note", "order_id"
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
