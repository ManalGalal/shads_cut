<?php

namespace App\Http\Requests;

use App\Models\AppSetting;
use Illuminate\Foundation\Http\FormRequest;

class updateAppSetting extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        // making sure the appSetting exists in route 
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $app_setting = $this->route("appSetting");
        // you can only change the value of the main_app_setting
        if ($app_setting->main) {
            return [
                "value" => ["string"]
            ];
        }
        return [
            "name" => ["string", "unique:app_settings,name"],
            "value" => ["string"],
            "data_type" => ["string", "in:string,numeric,boolean,string_arr,numeric_arr,boolean_arr"],
            "main" => ["boolean"],
            "private" => ["boolean"]
        ];
    }
}
