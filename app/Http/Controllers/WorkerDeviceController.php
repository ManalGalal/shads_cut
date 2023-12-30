<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUserDevice;
use App\Http\Requests\updateUserDevice;
use App\Models\WorkerDevice;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class WorkerDeviceController extends Controller {
    use HttpErrors;
    public function create(createUserDevice $request) {
        $validated = $request->validated();
        $validated["worker_id"] = $request->user()->id;
        WorkerDevice::create($validated);
        return response(null, 201);
    }
    public function update(updateUserDevice $request, WorkerDevice $workerDevice) {
        $validated = $request->validated();
        if ($workerDevice->worker_id !== $request->user()->id) {
            return $this->FORBIDDEN();
        }
        $workerDevice->update($validated);
        return response(null, 200);
    }
    public function getAll(Request $request) {
        $worker_devices = $request->user()->worker_devices;
        return response(["worker_devices" => $worker_devices]);
    }
}
