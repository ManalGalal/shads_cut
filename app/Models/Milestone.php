<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Milestone extends Model {
    use HasFactory, Localizable;
    protected $fillable = ["user_id", "points", "reason_en", "reason_ar", "order_id"];
    protected $localizable = ["reason"];
    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
}
