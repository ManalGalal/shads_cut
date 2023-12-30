<?php

namespace App\Http\Controllers;

use App\Http\Requests\adminCreateDayoff;
use App\Http\Requests\adminUpdateDayoff;
use App\Http\Requests\workerDayoffRequest;
use App\Models\DayOff;
use App\Models\Worker;
use App\Traits\DayOffTraits;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class DayOffController extends Controller {
    use HttpErrors, DayOffTraits;
    public function request(workerDayoffRequest $request) {
        $validated = $request->validated();
        $validated["worker_id"] = $request->user()->id;
        $day_off = DayOff::create($validated);
        $this->sendNotificationForAdmin($request->user());
        return response(["message" => __("messages.dayoff_requested"), "dayoff" => $day_off], 201);
    }
    public function create(adminCreateDayoff $request) {
        $validated = $request->validated();
        // make sure if admin is branch admin that he has the worker
        if ($request->user()->role == "normal") {
            $worker = Worker::where("id", $validated["worker_id"])
                ->where("branch_id", $request->user()->branch_id)
                ->first();
            if (!$worker) {
                return $this->UNAUTHORIZED();
            }
        }
        $day_off = DayOff::create($validated);
        return response(["message" => __("messages.dayoff_created"), "dayoff" => $day_off], 201);
    }
    public function update(adminUpdateDayoff $request, DayOff $dayOff) {
        $validated = $request->validated();
        $dayOff->update($validated);
        return response(["message" => __("messages.dayoff_updated")]);
    }
    public function myDaysoff(Request $request) {
        $daysoff = DayOff::where("worker_id", $request->user()->id)
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["daysoff" => $daysoff]);
    }
}
