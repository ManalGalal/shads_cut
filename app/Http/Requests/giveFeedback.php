<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class giveFeedback extends FormRequest {
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
            "feedback" => ["string"],
            "rating" => ["required", "numeric", "min:0", "max:5"]
        ];
    }
}
