<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createExpense extends FormRequest {
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
        $rules =  [
            "name_en" => ["required", "string"],
            "name_ar" => ["required", "string"],
            "amount" => ["required", "numeric", "min:0"],
            "expense_category_id" => ["required", "exists:expense_categories,id"],
            "note_en" => ["string"],
            "note_ar" => ["string"],
            "expense_date" => ["required", "date"]
        ];
        if ($this->user()->role === "super") {
            $rules["branch_id"] = ["required", "exists:branches,id"];
        }
        return $rules;
    }
}
