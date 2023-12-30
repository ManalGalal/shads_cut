<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReferalCode extends Model {
    use HasFactory;
    protected $fillable = ["user_id", "code", "used"];
    protected static function booted() {
        // generate Random code on creation
        static::creating(function ($code) {
            $attributes = $code->attributes;
            $generated_code = Str::random(5);
            $number_of_attempts = 0;
            while (ReferalCode::where("code", $generated_code)->exists()) {
                $generated_code = Str::random(5);
                $number_of_attempts++;
                if ($number_of_attempts > 5) {
                    throw new HttpException(503, "Something went wrong");
                }
            }
            $attributes["code"] = $generated_code;
            $code->attributes = $attributes;
        });
    }
    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
}
