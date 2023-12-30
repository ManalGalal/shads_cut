<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use HasFactory;
    protected $fillable = [
        "paymob_id", "paymob_auth_token",
        "paid_amount", "status", "order_id", "user_id"
    ];
    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
