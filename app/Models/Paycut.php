<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use App\Traits\BelongsToWorker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paycut extends Model {
    use HasFactory, BelongsToBranch, BelongsToWorker;
    protected $fillable = ["worker_id", "branch_id", "value", "note"];
}
