<?php

namespace App\Http\Controllers;

use App\Http\Requests\createAdmin;
use App\Http\Requests\updateAdmin;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {
    public function create(createAdmin $request) {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $admin = Admin::create($validated);
        return response(["admin" => __("messages.admin_created"), "admin" => $admin], 201);
    }
    public function update(updateAdmin $request, Admin $admin) {
        $validated = $request->validated();
        if (Arr::has($validated, "password")) {
            $validated["password"] = Hash::make($validated["password"]);
            foreach($admin->tokens as $token) {
                $token->revoke();
            }
            // Revoke Refresh token. 
            $refreshTokenRepository = app(\Laravel\Passport\RefreshTokenRepository::class);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            unset($admin["tokens"]);
        }
        $admin->update($validated);
        return response(["message" => __("messages.admin_updated"), "admin" => $admin]);
    }
    public function dashboardInfo(Request $request) {
        $info = $request->user()
            ->where("id", $request->user()->id)
            ->with(["branch", "roles:id,name", "roles.permissions:id,name"])
            ->first();
        return response(["info" => $info]);
    }
    public function delete(Admin $admin) {
        $admin->delete();
        return response(["message" => __("messages.admin_deleted")]);
    }
    public function getById(Admin $admin) {
        return response(["admin" => $admin]);
    }
    public function getAll() {
        return response(["admins" => Admin::all()]);
    }
}
