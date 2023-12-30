<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createOrder extends FormRequest {
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
            "type" => ["required", "string", "in:indoor,outdoor,home"],
            "branch_id" => ["required", "exists:branches,id"],
            "address_id" => ["required_if:type,outdoor,home", "exists:addresses,id"],
            "reservation_time" => ["required", "date_format:Y-m-d H:i:s", "after:yesterday"],
            "services" => ["required", "array", "min:1"],
            "services.*" => ["exists:services,id"],
            "workers" => ["array"],
            "workers.*" => ["exists:workers,id"],
            "coupon_code" => ["string", "exists:coupons,code", "notIn:SHADS"], // internal coupon only
        ];
    }
}
