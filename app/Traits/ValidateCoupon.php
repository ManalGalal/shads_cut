<?php


namespace App\Traits;

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;


trait ValidateCoupon {
    /**
     * @return bool|App\Models\Coupon::class 
     */
    public function isCouponValid($code, $user, $category = null) {
        $coupon = Coupon::where("code", $code)
            ->where("active", true)
            ->first();
        if (!$coupon) {
            return false;
        }
        if ($coupon->starts_at > Carbon::now()) {
            return false;
        }
        if ($coupon->expires_at < Carbon::now()) {
            return false;
        }
        if ($coupon->usage_number >= $coupon->usage_limit) {
            return false;
        }
        if ($coupon->membership) {
            if ($user->membership() !== $coupon->membership && $coupon->membership !== "ALL") {
                return false;
            }
        }
        if (!$this->countCouponUsagesPerUser($coupon, $user)) {
            return false;
        }
        if ($category) {
            // should indoor, outdoor, home 
            if ($coupon->category != "*" && $coupon->category != $category) {
                return false;
            }
        }
        return $coupon;
    }

    protected function countCouponUsagesPerUser($coupon, $user) {
        $usages = Order::where("user_id", $user->id)
            ->where("coupon_id", $coupon->id)
            ->count();
        if ($coupon->usages_per_user > $usages) {
            return true;
        }
        return false;
    }
}
