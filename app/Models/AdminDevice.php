<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDevice extends Model {
    use HasFactory;
    protected $fillable = ["admin_id", "device_id", "device_token"];
    protected $hidden = ["created_at", "updated_at"];

    protected static function booted() {

        static::creating(function ($adminDevice) {
            $attributes = $adminDevice->attributes;
            $found = AdminDevice::where("device_id", $attributes["device_id"])
                ->where("admin_id", $attributes["admin_id"])
                ->first();
            if ($found) {
                $found->delete();
            }
        });
    }
    public function admin() {
        return $this->belongsTo(Admin::class, "admin_id");
    }
}
