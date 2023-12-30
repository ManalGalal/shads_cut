<?php

namespace App\Traits;

use App\Models\Worker;

trait BelongsToWorker {

    public function worker() {
        return $this->belongsTo(Worker::class, "worker_id");
    }
}
