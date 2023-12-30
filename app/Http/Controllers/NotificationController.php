<?php

namespace App\Http\Controllers;

use App\Http\Requests\sendNotification;
use App\Models\Notification;
use App\Models\NotificationMessage;
use App\Traits\HttpErrors;
use App\Traits\NotificationTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationController extends Controller {
    use NotificationTraits, HttpErrors;
    public function send(sendNotification $request, $type) {
        if (!in_array($type, ["worker", "user", "admin"])) {
            return $this->BAD_REQUEST();
        }
        $validated = $request->validated();
        if (Arr::has($validated, "all") && $validated["all"]) {
            $class_name = "App\\Models\\" . ucfirst($type);
            $assigned = false;
            if ($type === "user") {
                $validated["ids"] = $class_name::where("status", "active")
                    ->get()->map(function ($user) {
                        return $user->id;
                    })->toArray();
                $assigned = true;
            }
            if (!$assigned) {
                $validated["ids"] = $class_name::all()->map(function ($type) {
                    return $type->id;
                })->toArray();
            }
        }
        $validated["internal"] = false; // External Notification made by admin
        $notification_message = $validated["new_notification"] == 0 ?
            NotificationMessage::where("id", $validated["notification_message_id"])->first() :
            NotificationMessage::create($validated);
        $this->sendNotification($notification_message, $type, $validated["ids"]);
        return response(["message" => __("messages.notification_sent")], 201);
    }
    public function getAll(Request $request) {
        $notifications = Notification::where("user_id", $request->user()->id)
            ->select(["id", "notification_message_id", "created_at"])
            ->with("notification_message")
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["notifications" => $notifications]);
    }
    public function getById(Request $request, Notification $notification) {
        if ($request->user()->id !== $notification->user_id) {
            return $this->FORBIDDEN();
        }
        $notification->update(["seen" => true]);
        $notification = Notification::where("id", $notification->id)
            ->with("notification_message")
            ->first();
        return response(["notification" => $notification]);
    }
    public function delete(Request $request, Notification $notification) {
        if ($request->user()->id !== $notification->user_id) {
            return $this->FORBIDDEN();
        }
        $notification->delete();
        return response(["message" => __("messages.notification_deleted")]);
    }
}
