<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchService;
use App\Models\Category;
use App\Models\Region;
use App\Models\Service;
use App\Models\Worker;
use App\Traits\HttpErrors;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller {
    use HttpErrors;
    public function getBranchesForRegion(Region $region) {
        $branches = Branch::select(["branches.id", "name_en", "name_ar", "address_en", "address_ar", "location_id"])
            ->join("branch_regions", "branches.id", "=", "branch_regions.branch_id")
            ->where("branch_regions.region_id", $region->id)
            ->where("branches.home", true)
            ->with(["work_days"])
            ->get();
        return response(["branches" => $branches]);
    }
    public function getCategories(Branch $branch) {
        $services = $branch->services()->orderBySortOrder()->where("home", true)->get();
        $category_ids = $services->map(function ($service) {
            return $service->category_id;
        });
        $categories = Category::whereIn("id", $category_ids)
            ->select(["id", "name_en", "name_ar"])
            ->orderBySortOrder()
            ->get();
        foreach ($categories as $category) {
            $category_services = [];
            foreach ($services as $service) {
                if ($service->category_id == $category->id) {
                    $category_services[] = $service;
                }
            }
            $category["services"] = $category_services;
        }
        return response(["categories" => $categories]);
    }
    //TODO: add home service validation;
    public function getWorkersForServices(Request $request, Branch $branch) {
        $service_ids = $request->query("services");
        if (!$service_ids) {
            return $this->BAD_REQUEST(__("errors.invalid_services"));
        }
        $service_ids = explode(",", $service_ids); // 1,2 => [1,2]
        // make sure every service id exist in branch 
        $branch_service_count = BranchService::whereIn("service_id", $service_ids)
            ->where("branch_id", $branch->id)
            ->count();
        if ($branch_service_count < count($service_ids)) {
            return $this->BAD_REQUEST(__("errors.invalid_services"));
        }
        //make sure they are home services 
        $home_services_count = Service::where("home", true)
            ->whereIn("id", $service_ids)
            ->count();
        if ($home_services_count < count($service_ids)) {
            return $this->BAD_REQUEST(__("errors.services_not_home"));
        }
        //make sure they all fall under the same category
        $category_ids = Service::select("category_id")
            ->whereIn("id", $service_ids)
            ->get()
            ->map(function ($service) {
                return $service->category_id;
            });

        // we know there is at least 1 service so we get one category id and compare it to the rest
        foreach ($category_ids as $category_id) {
            if ($category_ids[0] !== $category_id) {
                return $this->BAD_REQUEST(__("errors.invalid_services_category"));
            }
        }

        $reservation_time = $request->query("reservation_time");
        $date = new Carbon($reservation_time);
        if (!$date->isValid() || $date < Carbon::today()) {
            return $this->BAD_REQUEST(__("errors.invalid_date"));
        }
        $workers = Worker::servicesWorkersForBranch($service_ids, $branch->id, $date);
        return response(["workers" => $workers]);
    }
}
