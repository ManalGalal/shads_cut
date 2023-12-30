<?php

namespace App\Http\Controllers;

use App\Http\Requests\createBranch;
use App\Http\Requests\updateBranch;
use App\Models\Branch;
use App\Models\Location;
use Illuminate\Http\Request;

class BranchController extends Controller {
    public function create(createBranch $request) {
        $validated = $request->validated();
        $location = Location::create($validated);
        $validated["location_id"] = $location->id;
        $branch = Branch::create($validated);
        return response(["message" => __("messages.branch_created"), "branch" => $branch], 201);
    }
    public function update(updateBranch $request, Branch $branch) {
        $validated = $request->validated();
        $branch->update($validated);
        $location = $branch->location;
        $location->update($validated);
        return response(["message" => __("messages.branch_updated")]);
    }
    public function delete(Branch $branch) {
        $branch->delete();
        return response(["message" => __("messages.branch_deleted")]);
    }
    public function getById(Branch $branch) {
        $branch = Branch::where("id", $branch->id)
            ->with(["location", "regions"])->first();
        return response(["branch" => $branch]);
    }
    public function getAll() {
        $branches = Branch::with(["location", "regions"])->get();
        return response(["branches" => $branches]);
    }
}
