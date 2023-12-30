<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUserDevice;
use App\Http\Requests\updateUserDevice;
use App\Models\AdminDevice;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class AdminDeviceController extends Controller {
    use HttpErrors;
    public function create(createUserDevice $request) {
        $validated = $request->validated();
        $validated["admin_id"] = $request->user()->id;
        AdminDevice::create($validated);
        return response(null, 201);
    }
    public function update(updateUserDevice $request, AdminDevice $adminDevice) {
        $validated = $request->validated();
        if ($adminDevice->admin_id !== $request->user()->id) {
            return $this->FORBIDDEN();
        }
        $adminDevice->update($validated);
        return response(null, 200);
    }
    public function getAll(Request $request) {
        $admin_devices = $request->user()->admin_devices;
        return response(["admin_devices" => $admin_devices]);
    }
}
