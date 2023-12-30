<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchRegion;
use App\Models\Region;
use App\Traits\HttpErrors;

class BranchRegionController extends Controller {
    use HttpErrors;
    public function add(Branch $branch, Region $region) {
        // Note: added orWhere to make a region only assigned to one branch and branch assigned to only 1 region 
        $exists = BranchRegion::where("branch_id", $branch->id)
            ->where("region_id", $region->id)
            ->exists();
        if ($exists) {
            return $this->BAD_REQUEST(__("errors.branch_in_region"));
        }
        // special-case: when 
        if ($branch->is_van) {
            $exists = BranchRegion::where("branch_id", $branch->id)
                ->exists();
            if ($exists) {
                return $this->BAD_REQUEST(__("errors.branch_in_region"));
            }
            // if region has anymore branches that are vans return errors cause only one branch_van can exist in region
            $count = BranchRegion::where("region_id", $region->id)
                ->join("branches", "branch_id", "=", "branches.id")
                ->where("branches.is_van", true)
                ->count();
            if ($count > 0) {
                return $this->BAD_REQUEST(__("errors.region_has_van"));
            }
        }
        BranchRegion::create(["branch_id" => $branch->id, "region_id" => $region->id]);
        return response(["message" => __("messages.branch_added_to_region")]);
    }
    public function remove(Branch $branch, Region $region) {
        $exists = BranchRegion::where("branch_id", $branch->id)
            ->where("region_id", $region->id)
            ->exists();
        if (!$exists) {
            return $this->BAD_REQUEST(__("errors.branch_not_in_region"));
        }
        BranchRegion::where("branch_id", $branch->id)
            ->where("region_id", $region->id)
            ->delete();
        return response(["message" => __("messages.branch_removed_from_region")]);
    }
}
