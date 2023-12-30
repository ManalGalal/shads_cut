<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateService extends FormRequest {
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
            "name_en" => ["string"],
            "name_ar" => ["string"],
            "price" => ["numeric", "min:0"],
            "category_id" => ["exists:categories,id"],
            "home" => ["boolean"],
            "estimated_time" => ["numeric", "min:1", "max:1440"],
            "commission" => ["numeric", "min:0", "max:100"],
            "default_commission" => ["numeric", "min:0", "max:100"],
            "sort_order" => ["numeric", "min:1"]
        ];
    }
}
