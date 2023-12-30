<?php

namespace App\Http\Controllers;

use App\Http\Requests\createRole;
use App\Http\Requests\updateRole;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller {
    public function create(createRole $request) {
        $validated = $request->validated();
        $role = Role::create($validated);
        unset($role["guard_name"]);
        return response(["message" => __("messages.role_created"), "role" => $role], 201);
    }
    public function update(updateRole $request, Role $role) {
        $validated = $request->validated();
        $role->update($validated);
        unset($role["guard_name"]);
        return response(["message" => __("messages.role_updated"), "role" => $role], 202);
    }
    public function addPermissionsToRole(Request $request, Role $role) {
        $role->givePermissionTo($request->input("permissions"));
        return response(["message" => __("messages.permissions_added")]);
    }
    public function removePermissionFromRole(Permission $permission, Role $role) {
        $role->revokePermissionTo($permission->id);
        return response(["message" => __("messages.permission_removed")]);
    }
    public function assignRoleToUser(Role $role, Admin $admin) {
        $admin->assignRole($role->id);
        return response(["message" => __("messages.role_assigned")]);
    }
    public function removeRoleFromUser(Role $role, Admin $admin) {
        $admin->removeRole($role->id);
        return response(["message" => __("messages.role_removed")]);
    }
    public function getAll() {

        return response(["roles" => Role::with("permissions:id,name")->get()]);
    }
}
