<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateCoupon extends FormRequest {
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
            "code" => ["string", "unique:coupons,code"],
            "value" => ["numeric", "min:0"],
            "starts_from" => ["date"],
            "expires_at" => ["date", "after:starts_from"],
            "category" =>  ["string", "in:indoor,outdoor,home,all"],
            "type" =>  ["string", "in:fixed,percentage"],
            "active" => ["boolean"],
            "usage_limit" => ["numeric", "min:1"],
            "usages_per_user" => ["numeric", "min:1"],
            "membership" => ["string", "in:PLAT,SILVER,GOLD,BASIC,ALL"],
            "special" => ["boolean"]
        ];
    }
}
