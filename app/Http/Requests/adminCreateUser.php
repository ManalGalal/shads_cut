<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminCreateUser extends FormRequest {
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
            "email" => ["required", "unique:users,email"],
            "phone" => ["required", "unique:users,phone"],
            "birth_date" => ["required", "date", "before:today"],
            "password" => ["required", "string", "min:8"],
            "profile_picture" => ["file","mimes:png,jpg"]
        ];
    }
}
