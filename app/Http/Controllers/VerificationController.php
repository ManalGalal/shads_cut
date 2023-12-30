<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

use App\Traits\Verification;

class VerificationController extends Controller {
    use Verification, HttpErrors;
    public function sendMessage(Request $request) {
        $phone = $request->input("phone");
        $forget_password = $request->input("forget_password");
        $update_phone = $request->input("update_phone");
        if (!$phone) {
            return $this->BAD_REQUEST(__("errors.invalid_phone"));
        }
        $user_already_exists = User::where("phone", $phone)
            ->exists();
        if ($user_already_exists && !$forget_password && !$update_phone) {
            return $this->BAD_REQUEST(__("errors.user_exist"));
        }
        $response = $this->sendVerificationMessage($phone);
        if ($response) {
            return response(["message" => __("messages.code_sent")]);
        }
        return $this->BAD_REQUEST(__("errors.invalid_phone"));
    }
    public function verify(Request $request) {
        $phone = $request->input("phone");
        $code = $request->input("code");
        if (!$phone) {
            return $this->BAD_REQUEST(__("errors.invalid_phone"));
        }
        if (!$code) {
            return $this->BAD_REQUEST(__("errors.invalid_code"));
        }
        $response = $this->checkVerificationCode($phone, $code);
        if ($response) {
            return response(["message" => __("messages.phone_verified")]);
        }
        return $this->BAD_REQUEST(__("errors.invalid_code"));
    }
}
