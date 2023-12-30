<?php

namespace App\Http\Controllers;

use App\Http\Requests\createSupportForm;
use App\Http\Requests\updateSupportForm;
use App\Models\Admin;
use App\Models\NotificationMessage;
use App\Models\SupportForm;
use App\Traits\NotificationTraits;
use Illuminate\Http\Request;

class SupportFormController extends Controller {
    use NotificationTraits;
    public function create(createSupportForm $request) {
        $validated = $request->validated();
        $validated["user_id"] = $request->user()->id;
        SupportForm::create($validated);
        $user = $request->user();
        $notification_message = NotificationMessage::create([
            "title_en" => "New Complaint", "title_ar" => "شكوي جديدة",
            "body_en" => "New Complaint from name: $user->name, phone: $user->phone",
            "body_ar" => "name: $user->name, phone: $user->phone شكوي جديدة من "
        ]);
        $admin_ids = Admin::where("role", "super")
            ->get()
            ->map(function ($admin) {
                return $admin->id;
            }) ?? [];
        $this->sendNotification($notification_message, "admin", $admin_ids);
        return response(["message" => __("messages.support_form_sent")], 201);
    }
    public function update(updateSupportForm $request, SupportForm $form) {
        $validated = $request->validated();
        $form->update($validated);
        return response(["message" => __("messages.support_form_updated"), "support_form" => $form]);
    }
    public function delete(SupportForm $form) {
        $form->delete();
        return response(["message" => __("messages.support_form_deleted")]);
    }
    public function getAll(Request $request) {
        $support_forms = SupportForm::orderByDesc("created_at")
            ->with(["user", "support_reason"])
            ->paginate($request->number)
            ->withQueryString();
        return response(["support_forms" => $support_forms]);
    }
    public function getById(SupportForm $form) {
        $support_form = SupportForm::where("id", $form->id)
            ->with(["user", "support_reason"])
            ->first();
        return response(["support_form" => $support_form]);
    }
}
