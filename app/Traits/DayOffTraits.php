<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\NotificationMessage;

trait DayOffTraits {
    use NotificationTraits;
    public function sendNotificationForAdmin($worker) {
        $notification_message = NotificationMessage::create([
            "title_en" => "Day off requested", "title_ar" => "طلب ليوم اجازة",
            "body_en" => "Dayf off requested form $worker->name",
            "body_ar" => "طلب ليوم اجازة من $worker->name"
        ]);
        $admin_ids = Admin::where("role", "super")
            ->orWhere("branch_id", $worker->branch_id)
            ->get()
            ->map(function ($admin) {
                return $admin->id;
            }) ?? [];
        $this->sendNotification($notification_message, "admin", $admin_ids);
    }
}
