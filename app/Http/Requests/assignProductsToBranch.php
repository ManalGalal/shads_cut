<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class assignProductsToBranch extends FormRequest {

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
            "products" => ["required", "array", "min:1"],
            "products.*.id" => ["required", "exists:products,id", "distinct"],
            "products.*.quantity" => ["numeric", "min:0"],
            "products.*.commission" => ["numeric", "min:0", "max:100"],
        ];
        if ($this->user()->isSuperAdmin()) {
            $rules["branch_id"] = ["required", "exists:branches,id"];
        }
        return $rules;
    }
}
