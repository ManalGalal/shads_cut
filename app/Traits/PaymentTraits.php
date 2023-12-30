<?php

namespace App\Traits;

use App\Models\OrderPaymentMethod;
use App\Models\Transaction;

trait PaymentTraits {
    use Paymob; 
    public function orderPayment($payment_method, $user, $order) {
        if ($payment_method === "wallet") {
            return $this->walletPayment($user, $order);
        }
        if ($payment_method === "card") {
            return $this->cardPayment($user, $order);
        }
    }

    protected function walletPayment($user, $order) {
        $paid_amount = $order->total_amount - $order->total_paid;
        if ($user->wallet <= $paid_amount) {
            $total_in_wallet = $user->wallet;
            $order->total_paid  += $total_in_wallet;
            $user->wallet = 0;
            $user->save();
            $order->update();
            OrderPaymentMethod::create([
                "order_id" => $order->id,
                "payment_method" => "wallet",
                "paid_amount" => $total_in_wallet
            ]);
            return response(["payment_key" => null]);
        }
        $order->total_paid += $paid_amount;
        $user->wallet -= $paid_amount;
        $order->update();
        $user->save();
        OrderPaymentMethod::create([
            "order_id" => $order->id,
            "payment_method" => "wallet",
            "paid_amount" => $paid_amount
        ]);
        return response(["payment_key" => null]);
    }
    protected function cardPayment($user, $order) {
        $paid_amount = $order->total_amount - $order->total_paid;
        $transaction = Transaction::create([
            "paid_amount" => $paid_amount,
            "order_id" => $order->id,
            "user_id" => $user->id
        ]);

        $payment_key = $this->pay(
            $transaction->id,
            $paid_amount,
            $user->email,
            $user->name,
            $user->name,
            $user->phone
        );
        if (!$payment_key) {
            return $this->SERVER_ERROR();
        }
        return response(["payment_key" => $payment_key]);
    }
}
