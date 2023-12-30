<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateStock extends FormRequest {
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
        $stock = $this->route("stock");
        return [
            "name_en" => ["string"],
            "name_ar" => ["string"],
            "image" =>  ["file", "mimes:png,jpg"],
            "quantity" => ["numeric", "min:0"],
            "description_en" => ["string"],
            "description_ar" => ["string"],
            "stock_availability" => ["boolean"],
            "multi_use" => ["boolean"],
            "use_times" => ["numeric", "min:1"],
            "left_over" => ["numeric", "min:0", "max:$stock->use_times"],
            "price" => ["numeric", "min:0"],
            "comission" => ["numeric", "min:0", "max:100"],
            "sku" => ["string"],
            "min_quantity" => ["numeric", "min:0"],
            "max_quantity" => ["required_with:min_quantity", "numeric", "min:0", "gt:min_quantity"]
        ];
    }
}
