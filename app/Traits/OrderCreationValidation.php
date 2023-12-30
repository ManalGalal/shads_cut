<?php

namespace App\Traits;

use App\Models\Address;
use App\Models\Branch;
use App\Models\BranchService;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait OrderCreationValidation {
    use HttpErrors, ValidateCoupon, MembershipTraits;
    public function orderValidation($request, $validated, $validate_address = true) {
        $type = $validated["type"];
        if ($type === "outdoor") {
            $this->outdoorOrder($validated);
        }
        if ($type === "indoor") {
            $this->indoorOrder($validated);
        }
        $this->validateBranchServices($validated);
        // validate that the services are home services 
        $this->validateServicesForHome($validated);
        $this->validateBranchWorkers($validated);
        if ($validate_address) {
            $this->validateAddress($request, $validated);
        }
    }
    public function indoorOrder($validated) {
        $branch = Branch::where("id", $validated["branch_id"])
            ->first();
        if ($branch->is_van) {
            throw new HttpException(400, __("errors.invalid_branch"));
        }
        return true;
    }
    public function outdoorOrder($validated) {
        $branch = Branch::where("id", $validated["branch_id"])
            ->first();
        if (!$branch->is_van) {
            throw new HttpException(400, __("errors.invalid_branch"));
        }
        return true;
    }
    public function homeOrder($validated) {
        $branch = Branch::where("id", $validated["branch_id"])
            ->first();
        if (!$branch->home) {
            throw new HttpException(400, __("errors.invalid_branch"));
        }
    }

    public function validateBranchServices($validated) {
        $count = BranchService::whereIn("service_id", $validated["services"])
            ->where("branch_id", $validated["branch_id"])
            ->count();
        if ($count < count($validated["services"])) {
            throw new HttpException(400, __("errors.invalid_services"));
        }
        return true;
    }
    public function validateServicesForHome($validated) {
        if ($validated["type"] === "home") {
            $count = Service::whereIn("id", $validated["services"])
                ->where("home", true)
                ->count();
            if ($count < count($validated["services"])) {
                throw new HttpException(400, __("errors.invalid_services"));
            }
        }
        return true;
    }
    public function validateBranchWorkers($validated) {
        //TODO: add avaliablity

        // skip if no workers; 
        if (!Arr::has($validated, "workers")) {
            return true;
        }
        foreach ($validated["workers"] as $worker) {
            $worker = Worker::where("id", $worker)
                ->where("branch_id", $validated["branch_id"])
                ->first();
            if (!$worker) {
                throw new HttpException(400, "errors.worker_not_in_branch");
            }
            $worker_has_service = WorkerService::where("worker_id", $worker->id)
                ->whereIn("service_id", $validated["services"])
                ->exists();
            if (!$worker_has_service) {
                throw new HttpException(400, __("errors.invalid_worker"));
            }
            $worker->is_avaliable(new Carbon($validated["reservation_time"]));
            if (!$worker->avaliable) {
                throw new HttpException(400, __("errors.worker_not_avaliable"));
            }
        }
        return true;
    }
    public function validateAddress($request, $validated) {
        if ($validated["type"] === "outdoor" || $validated["type"] === "home") {

            $address = Address::where("id", $validated["address_id"])
                ->where("user_id", $request->user()->id)
                ->first();
            if (!$address) {
                throw new HttpException(400, __("errors.invalid_address"));
            }
            if ($validated["type"] === "home" && !$address->home) {
                throw new HttpException(400, __("errors.address_not_home"));
            }
            return true;
        }
        // if type is indoor just remove address_id
        unset($validated["address_id"]);
        return true;
    }
    public function calculateTotalAmount($validated) {
        $service_ids = $validated["services"];
        $services = Service::whereIn("id", $service_ids)
            ->select("price")
            ->get();
        $total_amount = 0;
        foreach ($services as $service) {
            $total_amount += $service->price;
        }
        return $total_amount;
    }
    /**
     * @api use to applyCoupon When creating the order 
     * also use it after caluclating the total amount
     */
    public function applyCoupon($request, $validated) {
        $user = Arr::has($validated, "user_id") ? User::where("id", $validated["user_id"])->first() : $request->user();

        if (!Arr::has($validated, "coupon_code") && $user->shads) {
            // if user is shads add SHADS to coupon_code
            if ($this->isCouponValid("SHADS", $user)) {
                $validated["coupon_code"] = "SHADS";
            }
        }
        // check if user has coupons code 
        if (!Arr::has($validated, "coupon_code")) {
            return $validated;
        }
        // If request is coming from dashboard
        $coupon = $this->isCouponValid($validated["coupon_code"], $user);
        if (!$coupon) {
            throw new HttpException(400, __("errors.invalid_coupon"));
        }
        $this->incrementCouponUsage($coupon);
        $validated["coupon_id"] = $coupon->id;
        if ($coupon->type == "fixed") {
            if ($coupon->value > $validated["total_amount"]) {

                $validated["discounted_amount"] = $validated["total_amount"];
                $validated["total_amount"] = 0;
                return $validated;
            }
            $validated["discounted_amount"] = $coupon->value;
            $validated["total_amount"] -= $coupon->value;
            return $validated;
        }
        $discounted_amount = $validated["total_amount"] * $coupon->value / 100;
        $validated["discounted_amount"] = $discounted_amount;
        $validated["total_amount"] -= $discounted_amount;
        return $validated;
    }
    public function incrementCouponUsage($coupon) {
        $coupon->usage_number++;
        $coupon->save();
    }
}
