<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createBranch extends FormRequest {
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
            "address_en" => ["required", "string"],
            "address_ar" => ["required", "string"],
            "long" => ["required", "numeric", "min:0", "max:360"],
            "lat" => ["required", "numeric", "min:0", "max:360"],
            "is_van" => ["boolean"],
            "home" => ["boolean"]
        ];
    }
}
