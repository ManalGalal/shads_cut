<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminOrderPayment extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "payment_method" => ["required", "string", "in:cash,card,card_on_arrival,mobile_wallet,wallet,lucky,waffarha"],
        ];
    }
}
