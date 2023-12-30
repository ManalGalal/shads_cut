<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportForm extends Model {
    use HasFactory;
    protected $fillable = ["user_id", "support_reason_id", "subject", "message", "status"];

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
    public function support_reason() {
        return $this->belongsTo(SupportReason::class, "support_reason_id");
    }
}
