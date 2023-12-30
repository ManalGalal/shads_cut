<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorkingDays;
use App\Http\Requests\updateWorkDay;
use App\Models\WorkDay;
use App\Models\Worker;
use App\Traits\HttpErrors;
use App\Traits\WorkDayTraits;
use Illuminate\Support\Arr;

class WorkDayController extends Controller {
    use HttpErrors, WorkDayTraits;
    protected $days_of_the_week = ["fri", "sat", "sun", "mon", "tue", "wed", "thu"];
    public function create(createWorkingDays $request) {
        $validated = $request->validated();
        $this->validateWorkDays($request);
        foreach ($this->days_of_the_week as $day) {
            $from_exists = in_array("from", $validated[$day]);
            $to_exists = in_array("to", $validated[$day]);
            if ($validated["for_branch"]) {
                $validated["branch_id"] = $request->user()->isSuperAdmin() ? $validated["branch_id"] : $request->user()->branch_id;
                $validated["worker_id"] = null;
            }
            if (Arr::has($validated, "worker_id") && $validated["worker_id"]) {
                $validated["branch_id"] = null;
            }
            WorkDay::create([
                "branch_id" => $validated["branch_id"],
                "worker_id" => $validated["worker_id"],
                "day" => $day,
                "on" => $validated[$day]["on"],
                "from" => $from_exists ? $validated[$day]["from"] : "09:00",
                "to" => $to_exists ?  $validated[$day]["to"] : "17:00"
            ]);
        }
        return response([
            "message" => __("messages.work_days_created")
        ], 201);
    }
    public function update(updateWorkDay $request, WorkDay $workDay) {
        $validated = $request->validated();
        $workDay->update($validated);
        return response(["message" => __("messages.workday_updated")]);
    }
    public function delete(WorkDay $workDay) {
        $workDay->delete();
        return response(["message" => __("messages.workday_deleted")]);
    }
    public function getForWorker(Worker $worker) {
        return response(["work_days" => $worker->work_days]);
    }
}
