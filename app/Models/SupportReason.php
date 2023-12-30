<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportReason extends Model {
    use HasFactory, Localizable, SoftDeletes;
    protected $fillable = ["reason_en", "reason_ar"];
    protected $localizable = ["reason"];
}
