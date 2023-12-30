<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOff extends Model {
    use HasFactory;

    protected $fillable = ["worker_id", "day", "status", "reason"];
    public function worker() {
        return $this->belongsTo(Worker::class);
    }
}
