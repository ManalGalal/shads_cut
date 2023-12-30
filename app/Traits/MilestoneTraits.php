<?php

namespace App\Traits;

use App\Models\AppSetting;
use App\Models\Milestone;
use App\Models\ReferalCode;
use App\Models\User;
use Illuminate\Support\Arr;

trait MilestoneTraits {
    /**
     * @deprecated This function will be replaced soon
     */
    public function pointsAfterOrderCreation($validated) {
        if ($validated["payment_method"] === "wallet") {
            return;
        }
        $user_id = $validated["user_id"];
        $total_amount = $validated["total_amount"];
        $order_money_to_points = AppSetting::where("name", "ORDER_MONEY_TO_POINTS")
            ->first();

        $order_money_to_points = $order_money_to_points ? $order_money_to_points->value : "2";
        $points = $total_amount * $order_money_to_points;
        $this->createMileStone($user_id, $points, "From last order", "بعد اخر حجز");
    }
    public function pointsAfterRegisteration($user_id) {
        $points_after_registeration = AppSetting::where("name", "POINTS_AFTER_REGISTERATION")
            ->first();
        $points = $points_after_registeration ? $points_after_registeration->value : 50;

        $this->createMileStone($user_id, $points, "After registration", "بعد تسجيل الدخول");
        return true;
    }
    public function pointsOnReferal($validated) {
        if (!Arr::has($validated, "referal_code")) {
            return;
        }
        $code = ReferalCode::where("code", $validated["referal_code"])
            ->where("used", false)
            ->first();
        if ($code) {
            $code->update(["used" => true]);
            $points_for_referal = AppSetting::where("name", "REFERAL_POINTS")
                ->first();
            $points = $points_for_referal ? $points_for_referal->value : 50;
            $this->createMileStone($code->user_id, $points, "Referal points", "نقاظ ترشيح مستخدم جديد");
        }
    }
    public function createMileStone($user_id, $points, $reason_en, $reason_ar) {
        $milestone =  Milestone::create(["user_id" => $user_id, "points" => $points, "reason_en" => $reason_en, "reason_ar" => $reason_ar]);
        $user = User::where("id", $user_id)->first();
        $user->points += $points;
        $user->save();
        return $milestone;
    }
}
