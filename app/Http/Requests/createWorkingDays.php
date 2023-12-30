<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createWorkingDays extends FormRequest {
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
        $days_of_the_week = ["fri", "sat", "sun", "mon", "tue", "wed", "thu"];
        // rules = [] 
        // one element should be  ["day.on" => ["required", "boolean"],
        // "day.from" => ["required", "Hour" ] , "day.to" => ["required", "hour"]]];

        // you get the idea of the rules 
        $rules = [
            "for_branch" => ["required", "boolean"]
        ];
        foreach ($days_of_the_week as $day) {
            $rules[$day . ".on"] = ["required", "boolean"];
            $rules[$day . ".from"] = ["required_if:$day.on,true", "date_format:H:i",];
            $rules[$day . ".to"] = ["required_if:$day.on,true", "date_format:H:i"];
        }

        $rules["worker_id"] = ["required_if:for_branch,false", "exists:workers,id"];
        if ($this->user()->isSuperAdmin()) {
            $rules["branch_id"] = ["required_if:for_branch,true", "exists:branches,id"];
        }
        return $rules;
    }
}
