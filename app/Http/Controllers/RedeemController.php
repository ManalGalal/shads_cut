<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\RedeemHistory;
use App\Models\User;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class RedeemController extends Controller {
    use HttpErrors;
    public function myPoints(Request $request) {
        $points = $request->input("points");
        $result = $this->redeemPoints($points, $request->user());
        if ($result !== "success") {
            return $result;
        }
        return response(["message" => __("messages.points_redeemed")]);
    }
    public function userPoints(Request $request, User $user) {
        $points = $request->input("points");
        $result = $this->redeemPoints($points, $user);
        if ($result !== "success") {
            return $result;
        }
        return response(["message" => __("messages.points_redeemed")]);
    }
    private function redeemPoints($points, $user) {
        $min_points_to_redeem = AppSetting::where("name", "MIN_POINTS_TO_REDEEM")
            ->first();
        if (!$min_points_to_redeem || !is_numeric($min_points_to_redeem->value)) {
            return $this->SERVER_ERROR();
        }
        if (!$points || !is_numeric($points)) {
            return $this->BAD_REQUEST(__("errors.redeem_failed"));
        }
        if ($points < $min_points_to_redeem->value) {
            return $this->BAD_REQUEST(__("errors.min_points_to_redeem", ["points" => $min_points_to_redeem->value]));
        }
        if ($points > $user->points) {
            return $this->BAD_REQUEST(__("errors.not_enough_points"));
        }
        $points_to_wallet = AppSetting::where("name", "POINTS_TO_WALLET")
            ->first();
        if (!$points_to_wallet || !is_numeric($points_to_wallet->value)) {
            return $this->SERVER_ERROR();
        }
        $user->wallet += $points * $points_to_wallet->value;
        $user->points -= $points;
        $user->save();
        RedeemHistory::create(["user_id" => $user->id, "points" => $points, "wallet_money" => $points * $points_to_wallet->value]);
        return "success";
    }
}
