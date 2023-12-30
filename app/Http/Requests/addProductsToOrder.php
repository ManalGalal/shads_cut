<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addProductsToOrder extends FormRequest {
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
            "products" => ["required", "array", "min:1"],
            "products.*.id" => ["required", "exists:products,id", "distinct"],
            "products.*.quantity" => ["required", "min:1"]
        ];
    }
}
