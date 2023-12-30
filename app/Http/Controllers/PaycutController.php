<?php

namespace App\Http\Controllers;

use App\Http\Requests\createPaycut;
use App\Http\Requests\updatePaycut;
use App\Models\Paycut;
use App\Models\Worker;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class PaycutController extends Controller {
    use HttpErrors;
    public function create(createPaycut $request) {
        $validated = $request->validated();
        $worker = Worker::where("id", $validated["worker_id"])
            ->first();

        if ($request->user()->isBranchAdmin() && $worker->branch_id !== $request->user()->branch_id) {
            return $this->UNAUTHORIZED();
        }
        $validated["branch_id"] = $worker->branch_id;
        $paycut = Paycut::create($validated);
        return response(["message" => __("messages.paycut_created"), "paycut" => $paycut], 201);
    }
    public function update(updatePaycut $request, Paycut $paycut) {
        $validated = $request->validated();
        $paycut->update($validated);
        return response(["message" => __("messages.paycut_updated"), "paycut" => $paycut]);
    }
    public function delete(Paycut $paycut) {
        $paycut->delete();
        return response(["message" => __("messages.paycut_deleted")]);
    }
    public function getById(Paycut $paycut) {
        $paycut = Paycut::where("id", $paycut->id)
            ->with("worker")
            ->first();
        return response(["paycut" => $paycut]);
    }
    public function getAll(Request $request) {
        $paycuts = Paycut::where("branch_id", $request->user()->branch_id)
            ->with("worker")
            ->get();
        return response(["paycuts" => $paycuts]);
    }
}
