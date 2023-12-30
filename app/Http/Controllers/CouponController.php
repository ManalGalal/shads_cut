<?php

namespace App\Http\Controllers;

use App\Http\Requests\createCoupon;
use App\Http\Requests\updateCoupon;
use App\Models\Coupon;
use App\Traits\ValidateCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    use ValidateCoupon;
    public function create(createCoupon $request) {
        $validated = $request->validated();
        if ($validated["type"] === "percentage") {
            $validated["value"] = $request->validate(["value" => ["required", "numeric", "min:0", "max:100"]])["value"];
        }
        $coupon = Coupon::create($validated);
        return response(["message" => __("messages.coupon_created"), "coupon" => $coupon], 201);
    }
    public function update(updateCoupon $request, Coupon $coupon) {
        $validated = $request->validated();
        $coupon->update($validated);
        return response(["message" => __("messages.coupon_updated"), "coupon" => $coupon]);
    }
    public function delete(Coupon $coupon) {
        $coupon->delete();
        return response(["message" => __("messages.coupon_deleted")]);
    }
    public function getById(Coupon $coupon) {
        return response(["coupon" => $coupon]);
    }
    public function getAll(Request $request) {
        $coupons = Coupon::orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["coupons" => $coupons]);
    }
    public function isValidCoupon(Request $request, $code) {
        $is_valid = true;
        if (!$this->isCouponValid($code, $request->user())) {
            $is_valid = false;
        }
        $coupon = Coupon::select(["type", "value"])
            ->where("code", $code)
            ->first();
        return response(["is_valid" => $is_valid, "coupon" => $coupon]);
    }
}
