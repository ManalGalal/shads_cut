<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppSetting extends Model {
    use HasFactory;
    protected $fillable = ["name", "value", "main", "private", "data_type"];
    protected $hidden = ["created_at", "updated_at"];
    // TODO: validate attribute->value on updating 
    protected static function booted() {
        // generate Random code on creation
        static::creating(function ($app_setting) {

            AppSetting::validateAttributes($app_setting);
        });
        static::updating(function ($app_setting) {
            AppSetting::validateAttributes($app_setting);
        });
    }
    public function getValueAttribute($value) {
        if (Str::contains($this->data_type, ["string_arr", "numeric_arr", "boolean_arr"])) {
            return explode(",", $value);
        }
        return $value;
    }
    public static function validateAttributes($app_setting) {
        $attributes = $app_setting->attributes;
        if (Arr::has($attributes, ["value", "data_type"])) {
            if ($attributes["data_type"] === "numeric") {
                if (!is_numeric($attributes["value"])) {
                    throw new HttpException(400, __("errors.value_not_number"));
                }
            }
            if ($attributes["data_type"] === "boolean") {
                if ($attributes["value"] != "1" && $attributes["value"] != "0") {
                    throw new HttpException(400, __("errors.value_not_boolean"));
                }
            }
            if ($attributes["data_type"] === "numeric_arr") {
                $values = explode(",", $attributes["value"]);
                foreach ($values as $value) {
                    if (!is_numeric($value)) {
                        throw new HttpException(400, __("errors.value_not_number"));
                    }
                }
            }
            if ($attributes["data_type"] === "boolean_arr") {

                $values = explode(",", $attributes["value"]);
                foreach ($values as $value) {

                    if ($value != "1" && $value != "0") {
                        throw new HttpException(400, __("errors.value_not_boolean"));
                    }
                }
            }
        }
    }
}
