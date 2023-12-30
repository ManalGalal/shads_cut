<?php

namespace App\Http\Controllers;

use App\Http\Requests\adminCreateUser;
use App\Http\Requests\adminUpdateUser;
use App\Http\Requests\createUser;
use App\Http\Requests\forgetPasswordRequest;
use App\Http\Requests\updatePhone;
use App\Http\Requests\updateUser;
use App\Http\Requests\uploadProfilePicture;
use App\Models\User;
use App\Traits\DeleteFiles;
use App\Traits\HttpErrors;
use App\Traits\MilestoneTraits;
use App\Traits\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    use DeleteFiles, Verification, HttpErrors, MilestoneTraits;
    public function create(createUser $request) {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);
        $this->pointsAfterRegisteration($user->id);
        $this->pointsOnReferal($validated);
        return response(["message" => __("messages.user_created")], 201);
    }
    public function uploadProfilePicture(uploadProfilePicture $request) {
        $user = $request->user();
        $path = $request->file("profile_picture")->store("/user/profile_pictures");
        $this->deleteFile($user["profile_picture"]);
        $user["profile_picture"] = $path;
        $user->save();
        return response(["message" => __("messages.profile_picture_uploaded")]);
    }
    public function update(updateUser $request) {
        $validated = $request->validated();
        $request->user()->update($validated);
        return response(["message" => __("messages.profile_updated")]);
    }
    public function getProfile(Request $request) {
        return response(["profile" => $request->user()->profile()]);
    }
    public function forgetPassword(forgetPasswordRequest $request) {
        $validated = $request->validated();
        $user = User::where("phone", $validated["phone"])
            ->first();
        $valid_code = $this->checkIfCodeIsValid($user->phone, $validated["code"]);
        if (!$valid_code) {
            return $this->BAD_REQUEST(__("errors.invalid_code"));
        }
        $new_password = Hash::make($validated["password"]);
        $user->update(["password" => $new_password]);
        return response(["message" => __("messages.password_changed")]);
    }
    public function changePassword(Request $request) {
        $old_password = $request->input("old_password");
        $new_password = $request->input("new_password");
        if (!$old_password || !$new_password) {
            return $this->BAD_REQUEST(__("errors.invalid_password"));
        }
        $user = $request->user();

        if (!Hash::check($old_password, $user->password)) {
            return $this->BAD_REQUEST(__("errors.incorrect_password"));
        }
        if (strlen($new_password) < 8) {
            return $this->BAD_REQUEST(__("errors.password_8c"));
        }
        if ($new_password === $old_password) {
            return $this->BAD_REQUEST(__("errors.password_match"));
        }
        $hashed_new_password = Hash::make($new_password);
        $user->update(["password" => $hashed_new_password]);
        return response(["message" => __("messages.password_changed")]);
    }
    public function updatePhone(updatePhone $request) {
        $validated = $request->validated();
        $valid_code = $this->checkIfCodeIsValid($validated["phone"], $validated["code"]);
        if (!$valid_code) {
            return $this->BAD_REQUEST(__("errors.invalid_code"));
        }
        $request->user()->update(["phone" => $validated["phone"]]);
        return response(["message" => __("messages.phone_updated")]);
    }
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response(["message" => __("messages.logged_out")], 200);
    }
    public function createForAdmin(adminCreateUser $request) {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        if ($request->hasFile("profile_picture")) {
            $validated["profile_picture"] = $request->file("profile_picture")->store("/user/profile_pictures");
        }
        $validated["source"] = "dashboard";
        $user = User::create($validated);
        $this->pointsAfterRegisteration($user->id);
        return response(["message" => __("messages.user_created"), "user" => $user], 201);
    }
    public function updateForAdmin(adminUpdateUser $request, User $user) {
        $validated = $request->validated();
        if (Arr::has($validated, "password")) {
            $validated["password"] = Hash::make($validated["password"]);
        }

        if ($request->hasFile("profile_picture")) {
            $validated["profile_picture"] = $request->file("profile_picture")->store("/user/profile_pictures");
        }
        $user->update($validated);
        return response(["message" => __("messages.user_updated"), "user" => $user]);
    }
    public function getAll(Request $request) {
        $users = User::orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["users" => $users]);
    }
    public function getById(Request $request, User $user) {
        $user = User::where("id", $user->id)
            ->with(["orders"])
            ->first();
        return response(["user" => $user]);
    }
}
