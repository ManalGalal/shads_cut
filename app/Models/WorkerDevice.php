<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerDevice extends Model {
    use HasFactory;
    protected $fillable = ["worker_id", "device_id", "device_token"];
    protected $hidden = ["created_at", "updated_at"];

    protected static function booted() {

        static::creating(function ($workerDevice) {
            $attributes = $workerDevice->attributes;
            $found = WorkerDevice::where("device_id", $attributes["device_id"])
                ->where("worker_id", $attributes["worker_id"])
                ->first();
            if ($found) {
                $found->delete();
            }
        });
    }
    public function worker() {
        return $this->belongsTo(Worker::class, "worker_id");
    }
}
