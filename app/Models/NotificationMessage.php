<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model {
    use HasFactory, Localizable;
    protected $fillable = ["title_en", "title_ar", "body_en", "body_ar", "url", "internal"];
    protected $localizable = ["title", "body"];

    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}
