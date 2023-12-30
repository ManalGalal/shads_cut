<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller {
    public function getById(Permission $permission) {
        $permission = Permission::where("id", $permission->id)
            ->select(["id", "name"])
            ->with("roles")
            ->first();
        return response(["permission" => $permission]);
    }
    public function getAll(Request $request) {
        $permissions = Permission::select(["id", "name"])
            ->paginate($request->number)
            ->withQueryString();

        return response(["permissions" => $permissions]);
    }
}
