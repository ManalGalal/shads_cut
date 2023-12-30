<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateAddress extends FormRequest {
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
            "region_id" => ["exists:regions,id"],
            "name" => ["string"],
            "street" => ["string"],
            "home" => ["boolean"],
            "building" => ["numeric", "min:0"],
            "floor" => ["numeric", "min:0"],
            "appartment" => ["numeric", "min:0"],
            "comment" => ["string"]
        ];
    }
}
