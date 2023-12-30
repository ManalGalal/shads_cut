<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpErrors;
use App\Traits\MilestoneTraits;
use App\Traits\Verification;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

class SocialLoginController extends Controller {

    use HttpErrors, Verification, MilestoneTraits;
    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }
    public function facebookLogin(ServerRequestInterface $request) {
        $token = $request->getHeader("token")[0];
        if (!$token) {
            return $this->BAD_REQUEST("Invalid token");
        }

        $user = call_user_func(array(Socialite::driver("facebook"), "userFromToken"), $token);
        $is_user = User::where("fb_id", $user->id)
            ->orWhere("email", $user->email)
            ->first();
        if ($is_user) {
            $new_password = Str::random();
            $hashed_password = Hash::make($new_password);
            $is_user->update(["password" => $hashed_password]);
            return $this->loginRequest($request, $is_user, $new_password);
        }
        $request_body = $request->getParsedBody();
        $check_phone = $request_body ? $request_body["phone"] : null;
        $phone  = $this->validatePhone($check_phone);
        if (gettype($phone) != "string") {
            return $phone;
        }
        $new_password = Str::random();
        $new_user = User::create([
            "name" => $user->name,
            "email" => $user->email,
            "profile_picture" => $user->avatar_original,
            "password" => Hash::make($new_password),
            "phone" => $phone,
            "fb_id" => $user->id
        ]);
        $this->pointsAfterRegisteration($new_user->id);
        return $this->loginRequest($request, $new_user, $new_password);
    }


    public function googleLogin(ServerRequestInterface $request) {
        $token = $request->getHeader("token")[0];
        if (!$token) {
            return $this->BAD_REQUEST("Invalid token");
        }
        $user = call_user_func(array(Socialite::driver("google"), "userFromToken"), $token);

        $is_user = User::where("google_id", $user->id)
            ->orWhere("email", $user->email)
            ->first();
        if ($is_user) {
            $new_password = Str::random();
            $hashed_password = Hash::make($new_password);
            $is_user->update(["password" => $hashed_password]);
            return $this->loginRequest($request, $is_user, $new_password);
        }
        $request_body = $request->getParsedBody();
        $check_phone = $request_body ? $request_body["phone"] : null;
        $phone  = $this->validatePhone($check_phone);
        if (gettype($phone) != "string") {
            return $phone;
        }
        $new_password = Str::random();
        $new_user = User::create([
            "name" => $user->name,
            "email" => $user->email,
            "profile_picture" => $user->avatar,
            "password" => Hash::make($new_password),
            "phone" => $phone,
            "google_id" => $user->id
        ]);
        $this->pointsAfterRegisteration($new_user->id);
        return $this->loginRequest($request, $new_user, $new_password);
    }
    public function appleLogin(ServerRequestInterface $request) {
        $token = $request->getHeader("token")[0];
        if (!$token) {
            return $this->BAD_REQUEST("Invalid token");
        }

        $user = Socialite::driver("apple")
            ->userFromToken($token);
        $is_user = User::where("apple_id", $user->id)
            ->orWhere("email", $user->email)
            ->first();
        if ($is_user) {
            $new_password = Str::random();
            $hashed_password = Hash::make($new_password);
            $is_user->update(["password" => $hashed_password]);
            return $this->loginRequest($request, $is_user, $new_password);
        }
        $request_body = $request->getParsedBody();
        $check_phone = $request_body ? $request_body["phone"] : null;
        $phone  = $this->validatePhone($check_phone);
        if (gettype($phone) != "string") {
            return $phone;
        }
        $new_password = Str::random();
        $new_user = User::create([
            "name" => $user->name ?? explode("@", $user->email)[0],
            "email" => $user->email,
            "password" => Hash::make($new_password),
            "phone" => $phone,
            "apple_id" => $user->id
        ]);
        $this->pointsAfterRegisteration($new_user->id);
        return $this->loginRequest($request, $new_user, $new_password);
    }

    private function loginRequest($request, $user, $new_password) {
        $input = [];
        $input["password"] = $new_password;
        $input["phone"] = $user->phone;
        $request = $request->withParsedBody($input);
        return with(new AuthController($this->server, $this->tokens, $this->jwt))
            ->userLogin($request, "user");
    }
    public function validatePhone($phone) {
        if (!$phone) {
            return response(["is_user" => false, "message" => __("messages.phone_required")]);
        }
        if (User::where("phone", $phone)
            ->exists()
        ) {
            return response(["message" => __("errors.invalid_phone")]);
        }
        return $phone;
    }
}
