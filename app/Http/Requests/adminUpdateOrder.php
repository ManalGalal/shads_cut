<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class adminUpdateOrder extends FormRequest {
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
        $order = $this->route("order");
        if (!$order) {
            throw new NotFoundHttpException();
        }
        return [
            "reservation_time" => ["date_format:Y-m-d H:i:s", "after:yesterday"],
            "status" => ["string", "in:pending,scheduled,in_progress,canceled,completed"],
            "total_amount" => ["numeric", "min:0"],
            "total_paid" => ["numeric", "min:0"],
            "coupon_code" => ["string", "exists:coupons,code"],
            "started_at" => ["date_format:Y-m-d H:i:s"],
            "ended_at" => ["date_format:Y-m-d H:i:s"],
            "location_id" => ["exists:locations,id"],
            "invoice_generated" => ["boolean"]
        ];
    }
}
