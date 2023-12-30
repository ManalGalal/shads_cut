<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createCoupon extends FormRequest {

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
            "code" => ["required", "string", "unique:coupons,code"],
            "value" => ["required", "numeric", "min:0"],
            "starts_from" => ["required", "date"],
            "expires_at" => ["required", "date", "after:starts_from"],
            "category" =>  ["required", "string", "in:indoor,outdoor,home,all"],
            "type" =>  ["required", "string", "in:fixed,percentage"],
            "active" => ["boolean"],
            "usage_limit" => ["numeric", "min:1"],
            "usages_per_user" => ["numeric", "min:1"],
            "membership" => ["string", "in:PLAT,SILVER,GOLD,BASIC,ALL"],
            "special" => ["boolean"]
        ];
    }
}
