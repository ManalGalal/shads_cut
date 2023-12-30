<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use Lcobucci\JWT\Parser as JwtParser;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Str;

class AuthController extends Controller {
    use HttpErrors;
    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }
    public function userLogin(ServerRequestInterface $request, $type) {
        $request_body = $request->getParsedBody();
        $class_name = "App\\Models\\" . ucfirst($type); // user => User
        $types = Str::plural($type); // user => users will be used as provider name 
        $user = $class_name::where("phone", $request_body["phone"])->first();
        if (!$user) {
            return $this->NOT_FOUND(__("errors.user_not_found"));
        }
        if (!Hash::check($request_body["password"], $user->password)) {
            return $this->FORBIDDEN(__("errors.invalid_password"));
        }
        $client_info = DB::table("oauth_clients")
            ->select(["id as client_id", "secret as client_secret"])
            ->where("provider", $types)
            ->first();
        if (!$client_info) {
            return $this->SERVER_ERROR();
        }

        $data = [
            'grant_type' => 'password',
            "username" => $user->phone,
            "password" => $request_body["password"],
            'scope' => '',
            "client_id" => $client_info->client_id,
            "client_secret" => $client_info->client_secret
        ];
        $request = $request->withParsedBody($data);
        return with(new AccessTokenController($this->server, $this->tokens, $this->jwt))
            ->issueToken($request);
    }
    public function refreshToken(ServerRequestInterface $request, $type) {
        $types = Str::plural($type); // user => users; 
        $body = $request->getParsedBody();
        $refresh_token = $body["refresh_token"];
        if (!$refresh_token) {
            return $this->BAD_REQUEST("errors.invalid_operation");
        }
        $client_info = DB::table("oauth_clients")
            ->select(["id as client_id", "secret as client_secret"])
            ->where("provider", $types)
            ->first();
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'scope' => '',
            "client_id" => $client_info->client_id,
            "client_secret" => $client_info->client_secret
        ];
        $request = $request->withParsedBody($data);
        return with(new AccessTokenController($this->server, $this->tokens, $this->jwt))
            ->issueToken($request);
    }
}
