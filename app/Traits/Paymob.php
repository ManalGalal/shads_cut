<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Paymob {
    private function generateToken() {
        $url =  "https://accept.paymob.com/api/auth/tokens";
        $response = Http::post($url, ["api_key" => env("PAYMOB_API_KEY")]);
        if ($response->successful()) {
            return  $response->json()["token"];
        }
        return null;
    }
    /**
     * I think i will sit merchant order id for the transaction here; 
     */
    private function registerOrder($amount_cents, $auth_token, $merchant_order_id) {
        $url = "https://accept.paymob.com/api/ecommerce/orders";
        $data = [
            "auth_token" => $auth_token,
            "delivery_needed" => "false",
            "amount_cents" => $amount_cents,
            "currency" => "EGP",
            "merchant_order_id" => $merchant_order_id,
            "items" => [] // has to be sent as an empty array 
        ];
        $response = Http::post($url, $data);
        if ($response->successful()) {
            return $response->json()["id"];
        }
        return null;
    }
    /**
     * $id here is the id returned from the above function. 
     */
    private function generatePaymentKey($auth_token, $id, $amount_cents, $email, $first_name, $last_name, $phone) {
        $url = "https://accept.paymob.com/api/acceptance/payment_keys";
        $data = [
            "auth_token" => $auth_token,
            "amount_cents" => $amount_cents,
            "expiration" => 3600, // can be changed later
            "order_id" => $id,
            "billing_data" => [
                "email" =>  $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "phone_number" => $phone,
                "apartment" => "NA",
                "floor" => "NA",
                "street" => "NA",
                "building" => "NA",
                "shipping_method" =>  "NA",
                "postal_code" => "NA",
                "city" =>  "NA",
                "country" => "NA",
                "state" => "NA"
            ],
            "currency" => "EGP",
            "integration_id" => env("PAYMOB_ONLINE_CARD")
        ];
        $response = Http::post($url, $data);
        if ($response->successful()) {
            return $response->json()["token"];
        }
        return null;
    }
    public function pay($transaction_id, $paid_amount, $email, $first_name, $last_name, $phone) {
        if (!is_numeric($paid_amount)) {
            throw new Exception("Invalid paid amount");
        }
        // cast paid amount to float for conversion
        $paid_amount = floatval($paid_amount);
        $amount_cents = $paid_amount * 100;

        $auth_token = $this->generateToken();
        if (!$auth_token) {
            return null;
        }
        $id = $this->registerOrder($amount_cents, $auth_token, $transaction_id);
        if (!$id) {
            return null;
        }

        $payment_key = $this->generatePaymentKey($auth_token, $id, $amount_cents, $email, $first_name, $last_name, $phone);
        return $payment_key;
    }
}
