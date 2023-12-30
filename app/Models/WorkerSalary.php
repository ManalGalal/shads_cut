<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerSalary extends Model {
    use HasFactory;

    protected $fillable = [
        "worker_id", "salary_date", "expected_salary",
        "actual_salary", "total_paycuts",
        "total_additives", "notes_ar", "notes_en"
    ];

    public function worker() { 
        return $this->belongsTo(Worker::class);
    }
}
