<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createRegion extends FormRequest {
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
            //
            "name_en" => ["required", "string"],
            "name_ar" => ["required", "string"],
            "city_id" => ["required", "exists:cities,id"]
        ];
    }
}
