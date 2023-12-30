<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model {
    use HasFactory, Localizable;
    protected $fillable = ["name_en", "name_ar", "description_en", "description_ar"];
    protected $localizable = ["name", "description"];
}
