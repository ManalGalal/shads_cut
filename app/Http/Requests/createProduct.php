<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createProduct extends FormRequest {
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
            "info_en" => ["required", "string"],
            "info_ar" => ["required", "string"],
            "price" => ["required", "numeric", "min:0"],
            "featured" => ["boolean"],
            "category_id" => ["required", "exists:categories,id"],
            "sku" => ["required", "string"],
            "barcode" => ["required", "string"],
            "quantity" => ["required", "numeric", "min:0"],
            "commission" => ["numeric", "min:0", "max:100"],
            "brand_id" => ["required", "exists:brands,id"]
        ];
    }
}
