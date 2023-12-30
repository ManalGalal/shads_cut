<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminCreateOrder extends FormRequest {
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
            "user_id" => ["required", "exists:users,id"],
            "address_id" => ["exists:addresses,id"], // TODO:uncomment this 
            "reservation_time" => ["required", "date_format:Y-m-d H:i:s", "after:yesterday"],
            "services" => ["required", "array", "min:1"],
            "services.*" => ["exists:services,id"],
            "workers" => ["array"],
            "workers.*" => ["exists:workers,id"],
            "coupon_code" => ["string", "exists:coupons,code"],
            "status" => ["string", "in:pending,scheduled,in_progress,canceled,completed"],
            "total_amount" => ["numeric", "min:0"],
            "discounted_amount" => ["numeric", "min:0"],
            "started_at" => ["date_format:Y-m-d H:i:s"],
            "ended_at" => ["date_format:Y-m-d H:i:s"],
            "location_id" => ["exists:locations,id"]
        ];
    }
}
