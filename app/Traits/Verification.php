<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait Verification {

    public function sendVerificationMessage($phone) {
        try {
            $username = env('SMS_MISR_USERNAME');
            $password = env('SMS_MISR_PASSWORD');
            $msignature = env('SMS_MISR_MSIGNATURE');
            $token = env('SMS_MISR_TOKEN');
            $sender = env('SMS_MISR_SENDER');
            $code = rand(100000,999999);
            $url = "https://smsmisr.com/api/vSMS/?Username=$username&password=$password&Msignature=$msignature&Token=$token&Mobile=$phone&Code=$code&sender=$sender";
            $response = Http::post($url);
            if ($response->successful() && $response->json("code") === "4901") {
                DB::table("verified_phones")
                    ->insert(["phone" => $phone, "code" => $code, "created_at" => now(), "updated_at" => now()]);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function checkVerificationCode($phone, $code) {
        return $this->checkIfCodeIsValid($phone, $code);
    }
    public function checkIfCodeIsValid($phone, $code) {
        $verification = DB::table("verified_phones")
            ->where("phone", $phone)
            ->where("code", $code)
            ->where("created_at", "<=", now()->addMinutes(10))
            ->first();
        if (!$verification) {
            return false;
        }
        return true;
    }
}
