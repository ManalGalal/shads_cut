<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createStock extends FormRequest {
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
        $rules = [
            "name_en" => ["required", "string"],
            "name_ar" => ["required", "string"],
            "image" =>  ["file", "mimes:png,jpg"],
            "quantity" => ["required", "numeric", "min:0"],
            "description_en" => ["string"],
            "description_ar" => ["string"],
            "stock_availability" => ["required", "boolean"],
            "multi_use" => ["required", "boolean"],
            "use_times" => ["required_if:multi_use,true", "numeric", "min:1"],
            "price" => ["required", "numeric", "min:0"],
            "comission" => ["numeric", "min:0", "max:100"],
            "sku" => ["required", "string"],
            "min_quantity" => ["required", "numeric", "min:0"],
            "max_quantity" => ["required", "numeric", "gt:min_quantity"]
        ];
        // if user is superadmin then branch_id is required
        if ($this->user()->role === "super") {
            $rules["branch_id"] = ["required", "exists:branches,id"];
        }
        return $rules;
    }
}
