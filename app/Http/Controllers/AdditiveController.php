<?php

namespace App\Http\Controllers;

use App\Http\Requests\createAdditive;
use App\Http\Requests\updateAdditive;
use App\Models\Additive;
use App\Models\Worker;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class AdditiveController extends Controller {
    use HttpErrors;
    public function create(createAdditive $request) {
        $validated = $request->validated();
        $worker = Worker::where("id", $validated["worker_id"])
            ->first();
        if ($request->user()->isBranchAdmin() &&  $worker->branch_id !== $request->user()->branch_id) {
            return $this->UNAUTHORIZED();
        }
        $validated["branch_id"] = $worker->branch_id;
        $additive = Additive::create($validated);
        return response(["message" => __("messages.additive_created"), "additive" => $additive], 201);
    }
    public function update(updateAdditive $request, Additive $additive) {
        $validated = $request->validated();
        $additive->update($validated);
        return response(["message" => __("messages.additive_updated"), "additive" => $additive]);
    }
    public function delete(Additive $additive) {
        $additive->delete();
        return response(["message" => __("messages.additive_deleted")]);
    }
    public function getById(Additive $additive) {
        $additive = Additive::where("id", $additive->id)
            ->with("worker")
            ->first();
        return response(["additive" => $additive]);
    }
    public function getAll(Request $request) {
        $additives = Additive::where("branch_id", $request->user()->branch_id)
            ->with("worker")
            ->get();
        return response(["additives" => $additives]);
    }
}
