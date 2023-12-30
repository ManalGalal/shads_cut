<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateCategory extends FormRequest {
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
            "name_en" => ["string", "unique:categories,name_en"],
            "name_ar" => ["string", "unique:categories,name_ar"],
            "sort_order" => ["numeric", "min:1"]
        ];
    }
}
