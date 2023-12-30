<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createAdmin extends FormRequest {
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
            "email" => ["required", "string", "email", "unique:admins,email"],
            "phone" => ["required", "string", "unique:admins,phone"],
            "role" => ["required", "in:super,normal"],
            "branch_id" => ["required_if:role,normal", "exists:branches,id"],
            "password" => ["required", "string", "min:8"],
            "monthly_salary" => ["numeric", "min:0"]
        ];
    }
}
