<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createService extends FormRequest {
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
            "name_en" => ["required", "string"],
            "name_ar" => ["required", "string"],
            "price" => ["required", "numeric", "min:0"],
            "category_id" => ["required", "exists:categories,id"],
            "estimated_time" => ["required", "numeric", "min:1", "max:1440"],
            "home" => ["boolean"],
            "online" => ["boolean"],
            "commission" => ["numeric", "min:0", "max:100"],
            "default_commission" => ["numeric", "min:0", "max:100"],
            "sort_order" => ["numeric", "min:1"]
        ];
    }
}
