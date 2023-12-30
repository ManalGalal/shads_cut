<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model {
    use HasFactory;
    protected $fillable = ["worker_id", "branch_id", "day", "on", "from", "to"];

    public function worker() {
        return $this->belongsTo(Worker::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
