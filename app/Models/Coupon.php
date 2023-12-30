<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Coupon extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "code",
        "value",
        "starts_from",
        "expires_at",
        "category",
        "type",
        "active",
        "usage_limit",
        "usage_number",
        "special",
        "membership",
        "usages_per_coupon",
        "usages_per_user"
    ];
    protected static function booted() {
        static::deleting(function ($coupon) {
            if ($coupon->code == "SHADS") {
                throw new BadRequestHttpException(__("errors.shads_deleting"));
            }
        });
    }
    public function scopeActive($query) {
        return $query->where("active", true)
            ->where("expires_at", ">", now()->format("Y-m-d"));
    }
}
