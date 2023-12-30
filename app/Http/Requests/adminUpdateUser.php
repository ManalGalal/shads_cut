<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminUpdateUser extends FormRequest {
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
            "email" => ["unique:users,email"],
            "phone" => ["unique:users,phone"],
            "wallet" => ["numeric", "min:0"],
            "points" => ["numeric", "min:0"],
            "password" => ["string", "min:8"],
            "profile_picture" => ["file", "mimes:png,jpg"],
            "status" => ["string", "in:active,blocked"],
            "shads" => ["boolean"]
        ];
    }
}
