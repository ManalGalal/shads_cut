<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Region;
use App\Traits\CommonFlowTraits;

class OutdoorController extends Controller {
    use CommonFlowTraits;
    public function getBranchesForRegion(Region $region) {
        $branches = Branch::select(["branches.id", "name_en", "name_ar", "address_en", "address_ar", "location_id"])
            ->join("branch_regions", "branches.id", "=", "branch_regions.branch_id")
            ->where("branch_regions.region_id", $region->id)
            ->where("branches.is_van", true)
            ->with(["work_days"])
            ->get();
        return response(["branches" => $branches]);
    }
}
