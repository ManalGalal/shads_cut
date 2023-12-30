<?php

namespace App\Http\Controllers;

use App\Models\ReferalCode;
use App\Traits\AnalyticTraits;
use Illuminate\Http\Request;

class ReferalCodeController extends Controller {
    use AnalyticTraits;
    public function generateCode(Request $request) {
        $code = ReferalCode::create(["user_id" => $request->user()->id]);
        return response(["code" => $code->code]);
    }
    public function getAll(Request $request) {
        [$from, $to] = $this->dateFilter($request);
        $codes = ReferalCode::orderByDesc("created_at")
            ->where("created_at", ">=", $from)
            ->where("created_at", "<=", $to)
            ->with("user")
            ->paginate($request->number)
            ->withQueryString();
        return response(["codes" => $codes]);
    }
    public function delete(ReferalCode $code) {
        $code->delete();
        return response(["message" => __("messages.referal_code_deleted")]);
    }
}
