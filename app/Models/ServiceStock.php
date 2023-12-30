<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStock extends Model {
    use HasFactory;
    protected $fillable = ["service_id", "stock_id", "used_amount"];
    public function stock() {
        return $this->belongsTo(Stock::class);
    }
    public function service() {
        return $this->belongsTo(Service::class);
    }
}
