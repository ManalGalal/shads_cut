<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createAdditive extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "worker_id" => ["required", "exists:workers,id"],
            "value" => ["required", "numeric", "min:0"],
            "note" => ["string"]
        ];
    }
}
