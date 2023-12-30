<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentMethod extends Model {
    use HasFactory;
    protected $fillable = ["order_id", "payment_method", "paid_amount"];
    static function booted() {
        static::created(function ($model) {
            $attributes = $model->attributes;
            if ($attributes["payment_method"] === "wallet") {
                return;
            }
            $order = Order::where("id", $attributes["order_id"])
                ->first();

            $user = $order->user;
            $order_money_to_points = AppSetting::where("name", "ORDER_MONEY_TO_POINTS")
                ->first();
            if ($user->membership()) {
                $membership_order_money_to_points = AppSetting::where("name", $user->membership() . "_ORDER_MONEY_TO_POINTS")
                    ->first();
                if ($membership_order_money_to_points) {
                    $order_money_to_points = $membership_order_money_to_points;
                }
            }
            $order_money_to_points = $order_money_to_points ? $order_money_to_points->value : 2;
            $points = $attributes["paid_amount"] * $order_money_to_points;
            Milestone::create([
                "user_id" => $user->id, "points" => $points, "reason_en" => "From Last Order",
                "reason_ar" => "من اخر حجز", "order_id" => $order->id
            ]);
            $user->points += $points;
            $user->save();
        });
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
}
