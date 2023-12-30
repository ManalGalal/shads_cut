<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createWorker extends FormRequest {
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
            "name" => ["required", "string"],
            "age" => ["required", "numeric", "min:16"],
            "gender" => ["string", "in:male,female"],
            "email" => ["string", "email"],
            "phone" => ["required", "string", "unique:workers,phone"],
            "password" => ["required", "string"],
            "job_title" => ["required", "string"],
            "monthly_salary" => ["required", "numeric", "min:0"],
            "started_at" => ["required", "date"],
            "left_at" => ["data"],
            "lang" => ["string", "in:en,ar"]
        ];
    }
}
