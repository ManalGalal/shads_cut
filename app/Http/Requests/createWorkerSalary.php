<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createWorkerSalary extends FormRequest {
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
            "worker_id" => ["required", "exists:workers,id"],
            "salary_date" => ["required", "date_format:Y-m-d"],
            "expected_salary" => ["required", "numeric", "min:1"],
            "actual_salary" => ["required", "numeric", "min:1"],
            "total_paycuts" => ["required", "numeric"],
            "total_additives" => ["required", "numeric"],
            "notes_ar" => ["string"],
            "notes_en" => ["string"]
        ];
    }
}
