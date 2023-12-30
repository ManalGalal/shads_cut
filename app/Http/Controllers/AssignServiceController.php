<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchService;
use App\Models\Service;
use App\Traits\HttpErrors;

class AssignServiceController extends Controller {
    use HttpErrors;
    public function assignToBranch(Service $service, Branch $branch) {
        $found = BranchService::where("service_id", $service->id)
            ->where("branch_id", $branch->id)
            ->exists();
        if ($found) {
            return $this->BAD_REQUEST(__("errors.service_exists"));
        }
        BranchService::create(["service_id" => $service->id, "branch_id" => $branch->id]);
        return response(["message" => __("messages.service_assigned")]);
    }
    public function removeFromBranch(Service $service, Branch $branch) {
        $branch_service = BranchService::where("service_id", $service->id)
            ->where("branch_id", $branch->id);
        if (!$branch_service) {
            return $this->NOT_FOUND(__("errors.service_not_found"));
        }
        $branch_service->delete();
        return response(["message" => __("messages.service_removed")]);
    }
    
}
