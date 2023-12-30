<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateWorker extends FormRequest {
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
            "name" => ["string"],
            "age" => ["numeric", "min:16"],
            "gender" => ["string", "in:male,female"],
            "email" => ["string", "email"],
            "phone" => ["string", "unique:workers,phone"],
            "password" => ["string"],
            "job_title" => ["string"],
            "monthly_salary" => ["numeric", "min:0"],
            "flag" => ["nullable", "string"],
            "started_at" => ["date"],
            "left_at" => ["date"],
            "lang" => ["string", "in:en,ar"]
        ];
    }
}
