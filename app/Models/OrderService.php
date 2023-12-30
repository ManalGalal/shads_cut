<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model {
    use HasFactory;
    protected $fillable = ["service_id", "order_id", "added_by", "added_by_id"];
    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function service() {
        return $this->belongsTo(Service::class);
    }
}
