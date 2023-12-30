<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateBranch extends FormRequest {
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
            "address_en" => ["string"],
            "address_ar" => ["string"],
            "long" => ["numeric", "min:0", "max:360"],
            "lat" => ["numeric", "min:0", "max:360"],
            "is_van" => ["boolean"],
            "home" => ["boolean"]
        ];
    }
}
