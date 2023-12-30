<?php

namespace App\Traits;

use App\Models\NotificationMessage;
use App\Models\OrderPaymentMethod;

trait TransactionTraits {
    use NotificationTraits;
    public function sendNotificationOnSuccess($user) {
        if (!$user) {
            return;
        }
        $notification_message = NotificationMessage::create([
            "title_en" => "Order payment",
            "title_ar" => "دفع الطلب",
            "body_en" => "Order has been paid successfully",
            "body_ar" => "تم دفع الطلب بنجاح"
        ]);
        $this->sendNotification($notification_message, "user", [$user->id]);
    }
    public function updateOrderOnSuccess($transaction, $order) {
        $order->total_paid += $transaction->paid_amount;
        $order->save();
        OrderPaymentMethod::create([
            "order_id" => $order->id,
            "paid_amount" => $transaction->paid_amount,
            "payment_method" => "card"
        ]);
    }
}
