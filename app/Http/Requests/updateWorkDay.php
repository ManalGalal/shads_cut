<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateWorkDay extends FormRequest {
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
            "on" => ["boolean"],
            "from" => ["date_format:H:i"],
            "to" => ["date_format:H:i"]
        ];
    }
}
