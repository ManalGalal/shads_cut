<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class sendNotification extends FormRequest {
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
        $type = $this->route("type");
        $types = Str::plural($type);
        return [
            "new_notification" => ["required", "boolean"],
            "title_en" => ["required_if:new_notification,true", "string"],
            "body_en" => ["required_if:new_notification,true", "string"],
            "body_ar" => ["required_if:new_notification,true", "string"],
            "url" => ["required_if:new_notification,true", "string"],
            "title_ar" => ["required_if:new_notification,true", "string"],
            "notification_message_id" => ["required_if:new_notification,false", "exists:notification_messages,id"],
            "internal" => ["boolean"],
            "all" => ["boolean"],
            "ids" => ["required_without:all", "required_if:all,false", "array", "min:1"],
            "ids.*" => ["exists:$types,id", "distinct"]
        ];
    }
}
