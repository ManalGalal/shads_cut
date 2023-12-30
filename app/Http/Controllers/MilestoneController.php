<?php

namespace App\Http\Controllers;

use App\Http\Requests\createMilestone;
use App\Models\Milestone;
use App\Models\User;
use App\Traits\MilestoneTraits;
use Illuminate\Http\Request;

class MilestoneController extends Controller {
    use MilestoneTraits;
    public function myMileStones(Request $request) {
        $milestones = $request->user()
            ->milestones()
            ->orderByDesc("created_at")
            ->get();
        return response(["milestones" => $milestones]);
    }
    public function create(createMilestone $request) {
        
        $validated = $request->validated();
        $this->createMileStone($validated["user_id"], $validated["points"], $validated["reason_en"], $validated["reason_ar"]);
        return response(["message" => __("messages.milestone_created")]);
    }
    public function getAll(Request $request) {
        $milestones = Milestone::orderBy("created_at")
            ->with("user:id,name,profile_picture,phone")
            ->paginate($request->number)
            ->withQueryString();
        return response(["milestones" => $milestones]);
    }
    public function userMilestones(User $user) {
        $milestones = $user
            ->milestones()
            ->orderByDesc("created_at")
            ->get();
        return response(["milestones" => $milestones]);
    }
    public function getById(Milestone $milestone) {
        $milestone = Milestone::where("id", $milestone->id)
            ->with("user:id,name,profile_picture,phone")
            ->first();
        return response(["milestone" => $milestone]);
    }
    public function delete(Milestone $milestone) {
        $milestone->delete();
        return response(["message" => __("messages.milestone_deleted")]);
    }
}
