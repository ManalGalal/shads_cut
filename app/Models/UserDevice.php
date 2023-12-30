<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model {
    use HasFactory;
    protected $fillable = ["user_id", "device_id", "device_token"];
    protected $hidden = ["created_at", "updated_at"];

    protected static function booted() {

        static::creating(function ($userDevice) {
           $attributes = $userDevice->attributes;
           $found = UserDevice::where("device_id", $attributes["device_id"])
                            ->where("user_id", $attributes["user_id"])
                            ->first();
            if ($found){
                $found->delete();
            }
        });
    }
    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
}
