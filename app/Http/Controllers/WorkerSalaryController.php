<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorkerSalary;
use App\Http\Requests\updateWorkerSalary;
use App\Models\Worker;
use App\Models\WorkerSalary;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class WorkerSalaryController extends Controller {
    use HttpErrors;
    public function create(createWorkerSalary $request) {
        $validated = $request->validated();
        $worker = Worker::where("worker_id", $validated["worker_id"]);
        if ($request->user()->isBranchAdmin() && $worker->branch_id != $request->user()->branch_id) {
            return $this->FORBIDDEN();
        }
        WorkerSalary::create($validated);
        return response(["message" => __("messages.worker_salary_created")]);
    }
    public function update(updateWorkerSalary $request, WorkerSalary $workerSalary) {
        $validated = $request->validated();
        $worker = $workerSalary->worker;
        if ($request->user()->isBranchAdmin() && $worker->branch_id != $request->user()->branch_id) {
            return $this->FORBIDDEN();
        }
        $workerSalary->update($validated);
        return response(["message" => __("messages.worker_salary_update")]);
    }
}
