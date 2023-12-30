<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorker;
use App\Http\Requests\updateWorker;
use App\Http\Requests\uploadProfilePicture;
use App\Models\Additive;
use App\Models\Paycut;
use App\Models\Worker;
use App\Traits\DeleteFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkerController extends Controller {
    use DeleteFiles;
    public function create(createWorker $request) {
        $validated = $request->validated();
        if ($request->user()->role === "super") {
            $validated["branch_id"] = $request->validate(["branch_id" => ["required", "exists:branches,id"]])["branch_id"];
        } else {
            $validated["branch_id"] = $request->user()->branch_id;
        }
        $validated["password"] = Hash::make($validated["password"]);
        $worker = Worker::create($validated);
        return response(["message" => __("messages.worker_created"), "worker" => $worker], 201);
    }
    public function uploadProfilePicture(uploadProfilePicture $request, Worker $worker) {
        $request->validated();
        $this->deleteFile($worker->profile_picture);
        $validated["profile_picture"] = $request->file("profile_picture")->store("/worker/profile_pictures");
        $worker->update($validated);
        return response(["message" => __("messages.profile_picture_uploaded")]);
    }
    public function update(updateWorker $request, Worker $worker) {
        $validated = $request->validated();
        if (Arr::has($validated, "password")) {
            $validated["password"] = Hash::make($validated["password"]);
        }
        $worker->update($validated);
        return response(["message" => __("messages.worker_updated")]);
    }
    public function delete(Worker $worker) {
        $worker->delete();
        return response(["message" => __("messages.worker_deleted")]);
    }
    public function getById(Worker $worker) {
        $worker = Worker::where("id", $worker->id)
            ->with(["services"])
            ->first();
        return response(["worker" => $worker]);
    }
    public function getAll() {
        return response(["workers" => Worker::all()]);
    }
    public function getProfile(Request $request) {
        $profile = Worker::where("id", $request->user()->id)
            ->with(["branch:name_en,name_ar,id,address_en,address_ar", "worker_rates"])
            ->first();
        return response(["profile" => $profile]);
    }
    public function getSalary(Request $request) {
        $worker = $request->user();
        $original_salary = $worker->monthly_salary;
        $now = Carbon::now();
        $now->setMicroseconds(0);
        $now->setMillisecond(0);
        $now->setMinutes(0);
        $now->setHours(0);
        $now->setDays(1);
        $from = new Carbon($now);
        // if month is 2021-11 then we should look for things between 2020-11 and 2020-12 
        $to = $now->addMonth(1);

        $paycuts = Paycut::where("created_at", ">=", $from)
            ->where("created_at", "<", $to)
            ->where("worker_id", $request->user()->id)
            ->get();
        $additives = Additive::where("created_at", ">=", $from)
            ->where("created_at", "<", $to)
            ->where("worker_id", $request->user()->id)
            ->get();

        return response([
            "original_salary" => $original_salary, "paycuts" => $paycuts,
            "additives" => $additives,
            "accumlative_salary" => $worker->accumlative_salary
        ]);
    }
}
