<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use App\Traits\HasLocation;
use App\Traits\Localizable;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    /**
     * @Note Order Tax is calculated Automatically inside Loggable on total_amount change.
     */
    use HasFactory, HasLocation, BelongsToBranch, Loggable, Localizable;
    protected $fillable = [
        "type", "branch_id", "user_id", "coupon_id", "address_id", "location_id",
        "total_amount", "total_paid", "discounted_amount", "status", "reservation_time",
        "started_at", "ended_at", "feedback", "rating", "invoice_generated", "tax",
        "refund_reason_en", "refund_reason_ar", "source", "dashboard_discount"
    ];
    protected $loggable = ["status", "total_amount", "total_paid"];
    protected $localizable = ["refund_reason"];
    protected $hidden = ["pivot"];
    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
    public function services() {
        return $this->belongsToMany(Service::class, "order_services");
    }
    public function address() {
        return $this->belongsTo(Address::class, "address_id");
    }
    public function coupon() {
        return $this->belongsTo(Coupon::class, "coupon_id");
    }
    public function order_workers() {
        return $this->hasMany(OrderWorker::class, "order_id");
    }
    public function workers() {
        return $this->belongsToMany(Worker::class, "order_workers")
            ->distinct();
    }
    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }
    public function products() {
        return $this->belongsToMany(Product::class, "order_products");
    }
    public function cancellation_reason() {
        return $this->belongsTo(CancellationReason::class, "cancellation_reason_id");
    }
    public function stocks() {
        return $this->belongsToMany(Stock::class, "order_stocks");
    }
    public function payment_methods() {
        return $this->hasMany(OrderPaymentMethod::class);
    }
    public function logs() {
        return $this->hasMany(Log::class, "table_id")
            ->where("table_name", $this->getTable())
            ->select(["col_name", "value", "table_id"]);
    }
    public function milestones() {
        return $this->hasMany(Milestone::class);
    }
    /**
     * status occurrence is meant to see how many times status has occurred in order
     * like refunded  => should happen only once
     * in_progress can happen multiple times ...etc.
     * @return int
     */
    public static function statusOccurrence($id, $status) {
        return Log::where("table_name", "orders")
            ->where("table_id", $id)
            ->where("col_name", "status")
            ->where("value", $status)
            ->count();
    }
}
