<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancellationReason extends Model {
    use HasFactory, SoftDeletes, Localizable;

    protected $fillable = ["reason_en", "reason_ar"];
    protected $localizable = ["reason"];
    
}
