<?php

namespace App\Models;

use App\Traits\ServiceStockTraits;
use App\Traits\WorkerCommissionTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderWorker extends Model {
    use HasFactory;
    protected $fillable = ["order_id", "worker_id", "service_id", "completed", "start_time", "end_time"];
    protected static function booted() {

        static::updating(function ($model) {
            $original = $model->original;
            $attributes = $model->attributes;
            // meaning if this the first time to set OrderWorker as completed

            if ($attributes["completed"] && !$original["completed"]) {
                $service = Service::where("id", $attributes["service_id"])
                    ->first();
                $service_stocks = $service->service_stocks;
                foreach ($service_stocks as $service_stock) {
                    // update the internal stocks 
                    (new class {
                        use ServiceStockTraits;
                    })->updateStock($service_stock->stock, $service_stock->used_amount);
                }
                // give worker commission
                (new class {
                    use WorkerCommissionTraits;
                })->workerDefaultCommission($attributes["order_id"], $attributes["worker_id"], $attributes["service_id"]);
                (new class {
                    use WorkerCommissionTraits;
                })->workerAddedServiceCommission($attributes["order_id"], $attributes["service_id"]);
            }
        });
    }
    public function service() {
        return $this->belongsTo(Service::class);
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function worker() {
        return $this->belongsTo(Worker::class);
    }
}
