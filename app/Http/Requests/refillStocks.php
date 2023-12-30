<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class refillStocks extends FormRequest {
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
            "stocks" => ["required", "array", "min:1"],
            "stocks.*" => ["required", "exists:stocks,id", "distinct"]
        ];
    }
}
