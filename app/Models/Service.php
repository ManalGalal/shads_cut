<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {
    use HasFactory, Localizable;
    protected $fillable = [
        "name_en", "name_ar", "price", "category_id",
        "home", "online", "commission", "default_commission",
        "estimated_time", "sort_order"
    ];
    protected $localizable = ["name"];
    protected $hidden = ["pivot"];
    public function category() {
        return $this->belongsTo(Category::class, "category_id");
    }
    public function workers() {
        return $this->belongsToMany(Worker::class, "worker_services");
    }
    public function scopeOnline($query) {
        return $query->where("online", true);
    }
    public function stocks() {
        return $this->belongsToMany(Stock::class, "service_stocks");
    }
    /**
     * This is important so he can reterive the actual used_amount for each service in Branch Dashboard. 
     */
    public function branches() {
        return $this->belongsToMany(Branch::class, "branch_services");
    }
    public function service_stocks() {
        return $this->hasMany(ServiceStock::class);
    }
    public function scopeOrderBySortOrder($query) {
        return $query->orderBy('sort_order');
    }
}
