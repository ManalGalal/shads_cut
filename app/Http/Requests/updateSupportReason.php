<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateSupportReason extends FormRequest {
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
            "reason_en" => ["string", "unique:support_reasons,reason_en"],
            "reason_ar" => ["string", "unique:support_reasons,reason_ar"]
        ];
    }
}
