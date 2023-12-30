<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateProduct extends FormRequest {
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
            "info_en" => ["string"],
            "info_ar" => ["string"],
            "price" => ["numeric", "min:0"],
            "featured" => ["boolean"],
            "category_id" => ["exists:categories,id"],
            "sku" => ["string"],
            "barcode" => ["string"],
            "quantity" => ["numeric", "min:0"],
            "commission" => ["numeric", "min:0", "max:100"],
            "brand_id" => ["exists:brands,id"]
        ];
    }
}
