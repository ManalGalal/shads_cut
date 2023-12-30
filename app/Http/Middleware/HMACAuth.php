<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HMACAuth {
    use HttpErrors;
    protected $GET_KEYS = [
        "amount_cents",
        "created_at",
        "currency",
        "error_occured",
        "has_parent_transaction",
        "id",
        "integration_id",
        "is_3d_secure",
        "is_auth",
        "is_capture",
        "is_refunded",
        "is_standalone_payment",
        "is_voided",
        "order",
        "owner",
        "pending",
        "source_data_pan",
        "source_data_sub_type",
        "source_data_type",
        "success"

    ];
    protected $POST_KEYS = [
        "amount_cents",
        "created_at",
        "currency",
        "error_occured",
        "has_parent_transaction",
        "id",
        "integration_id",
        "is_3d_secure",
        "is_auth",
        "is_capture",
        "is_refunded",
        "is_standalone_payment",
        "is_voided",
        "order.id",
        "owner",
        "pending",
        "source_data.pan",
        "source_data.sub_type",
        "source_data.type",
        "success"

    ];
    /**
     * Middleware to handle HMAC authentication coming from paymob server
     * 
     * unfinished: if you need to save CARD Token this middleware will return 401 but you can update it later
     */
    public function handle(Request $request, Closure $next) {
        $method = $request->method();
        $sent_hmac = $method === "GET" ? $request->query("hmac") : $request->input("hmac");
        $data = $method === "GET" ?  $request->query() : $request->all()["obj"];
        $keys =  $method === "GET" ? $this->GET_KEYS : $this->POST_KEYS;
        if (!is_array($data)) {
            return $this->UNAUTHORIZED();
        }
        $unhashed_string = "";

        foreach ($keys as $key) {
            $value = Arr::get($data, $key);
            if ($value === false) {
                $value = "false";
            }
            if ($value === true) {
                $value = "true";
            }
            // echo "key => $key \n";
            // echo "actual value => " . Arr::get($data, $key) . "\n";
            // echo "value => $value \n";
            $unhashed_string = $unhashed_string . $value;
        }
        $generated_hmac = hash_hmac("sha512", $unhashed_string, utf8_encode(env("PAYMOB_HMAC_KEY")));
        if ($generated_hmac != $sent_hmac) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
