<?php

namespace App\Traits;

use App\Models\Additive;
use App\Models\Admin;
use App\Models\BranchProduct;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Paycut;
use App\Models\Product;
use App\Models\Region;
use App\Models\Service;
use App\Models\Stock;
use App\Models\WorkDay;
use App\Models\Worker;

trait BranchRelationships {
    public function admins() {
        return $this->hasMany(Admin::class);
    }
    public function workers() {
        return $this->hasMany(Worker::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function services() {
        return $this->belongsToMany(Service::class, "branch_services");
    }
    public function paycuts() {
        return $this->hasMany(Paycut::class);
    }
    public function additives() {
        return $this->hasMany(Additive::class);
    }
    public function regions() {
        return $this->belongsToMany(Region::class, "branch_regions");
    }
    public function stocks() {
        return $this->hasMany(Stock::class);
    }
    public function expenses() {
        return $this->hasMany(Expense::class);
    }
    public function branch_products() {
        return $this->hasMany(BranchProduct::class);
    }
    public function products() {
        return $this->belongsToMany(Product::class, "branch_products");
    }
    public function work_days() {
        return $this->hasMany(WorkDay::class);
    }
}
