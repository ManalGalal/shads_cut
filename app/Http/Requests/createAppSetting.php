<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createAppSetting extends FormRequest {
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
            "name" => ["required", "string", "unique:app_settings,name"],
            "value" => ["required", "string"],
            "data_type" => ["required", "string", "in:string,numeric,boolean,string_arr,numeric_arr,boolean_arr"],
            "main" => ["boolean"],
            "private" => ["boolean"]
        ];
    }
}
