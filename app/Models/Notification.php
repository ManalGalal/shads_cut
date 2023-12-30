<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ["notification_message_id", "user_id", "worker_id", "admin_id", "success", "seen", "message_id"];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function worker() {
        return $this->belongsTo(Worker::class);
    }
    public function admin() {
        return $this->belongsTo(Admin::class);
    }
    public function notification_message() {
        return $this->belongsTo(NotificationMessage::class);
    }
}
