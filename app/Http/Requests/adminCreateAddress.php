<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminCreateAddress extends FormRequest {
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
            "long" => ["required", "numeric", "min:0", "max:360"],
            "lat" => ["required", "numeric", "min:0", "max:360"],
            "user_id" => ["required", "exists:users,id"],
            "region_id" => ["required", "exists:regions,id"],
            "name" => ["required", "string"],
            "street" => ["required", "string"],
            "home" => ["boolean"],
            "building" => ["numeric", "min:0"],
            "floor" => ["numeric", "min:0"],
            "appartment" => ["numeric", "min:0"],
            "comment" => ["string"],
        ];
    }
}
