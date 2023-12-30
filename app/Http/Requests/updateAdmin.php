<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateAdmin extends FormRequest {
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
            "email" => ["string", "email", "unique:admins,email"],
            "phone" => ["string", "unique:admins,phone"],
            "branch_id" => ["exists:branches,id"],
            "role" => ["in:super,normal"],
            "password" => ["string", "min:8"],
            "monthly_salary" => ["numeric", "min:0"],
            "lang" => ["string", "in:en,ar"]
        ];
    }
}
