<?php

namespace App\Traits;

use App\Models\Coupon;

trait MembershipTraits {
    /**
     * @return Coupon
     */
    public function membershipCoupon($membership) {
        return Coupon::where("code", $membership . "_MEMBERSHIP")
            ->first();
    }
}
