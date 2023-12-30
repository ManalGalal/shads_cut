<?php

namespace App\Http\Controllers;

use App\Http\Requests\createSuperAdminRequest;
use App\Models\Admin;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class HiddenController extends Controller {
    public function createSuperAdmin(createSuperAdminRequest $request) {
        $validated = $request->validated();
        $validated["role"] = "super";
        $validated["password"] = Hash::make($validated["password"]);
        $admin = Admin::create($validated);
        return response(["message" => __("messages.admin_created"), "admin" => $admin], 201);
    }
    public function systemUsers() {
        $users = User::all();
        $admins = Admin::all();
        $workers = Worker::all();
        return response(["admins" => $admins, "users" => $users, "workers" => $workers]);
    }
}
