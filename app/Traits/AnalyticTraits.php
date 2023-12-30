<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait AnalyticTraits {
    public function dateFilter($request) {
        $from = $request->query("from") ?? "2021-03-01";
        $to = $request->query("to") ?? Carbon::now();
        return [$from, $to];
    }
}
