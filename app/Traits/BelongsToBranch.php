<?php

namespace App\Traits;

use App\Models\Branch;

trait BelongsToBranch {
    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }
}
