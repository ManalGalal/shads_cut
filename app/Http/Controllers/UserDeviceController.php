<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUserDevice;
use App\Http\Requests\updateUserDevice;
use App\Models\UserDevice;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class UserDeviceController extends Controller {
    use HttpErrors;
    public function create(createUserDevice $request) {
        $validated = $request->validated();
        $validated["user_id"] = $request->user()->id;
        UserDevice::create($validated);
        return response(null, 201);
    }
    public function update(updateUserDevice $request, UserDevice $userDevice) {
        $validated = $request->validated();
        if ($userDevice->user_id !== $request->user()->id) {
            return $this->FORBIDDEN();
        }
        $userDevice->update($validated);
        return response(null, 200);
    }
    public function getAll(Request $request) {
        $user_devices = $request->user()->user_devices;
        return response(["user_devices" => $user_devices]);
    }
}
