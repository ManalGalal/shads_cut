<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Traits\CommonFlowTraits;
use Illuminate\Http\Request;

class IndoorController extends Controller {
    use CommonFlowTraits;
    public function getBranches(Request $request) {
        $branches = Branch::select(["id", "name_en", "name_ar", "address_en", "address_ar", "location_id"])
            ->where("is_van", false)
            ->with(["location","work_days"])
            ->get();
        return response(["branches" => $branches]);
    }

}
