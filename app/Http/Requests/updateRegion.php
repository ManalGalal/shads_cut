<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateRegion extends FormRequest {
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
            "name_en" => ["string"],
            "name_ar" => ["string"],
            "city_id" => ["exists:cities,id"]
        ];
    }
}
