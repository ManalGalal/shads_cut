<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    use HasFactory, Localizable;
    protected $fillable = [
        "name_en", "name_ar", "amount", "expense_category_id",
        "branch_id", "note_en", "note_ar", "expense_date"
    ];
    protected $localizable = ["name", "note"];

    public function expense_category() {
        return $this->belongsTo(ExpenseCategory::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
