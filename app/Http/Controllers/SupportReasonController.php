<?php

namespace App\Http\Controllers;

use App\Http\Requests\createSupportReason;
use App\Http\Requests\updateSupportReason;
use App\Models\SupportReason;
use Illuminate\Http\Request;

class SupportReasonController extends Controller {
    public function create(createSupportReason $request) {
        $validated = $request->validated();
        $support_reason = SupportReason::create($validated);
        return response([
            "message" => __("messages.support_reason_created"),
            "support_reason" => $support_reason
        ], 201);
    }
    public function update(updateSupportReason $request, SupportReason $reason) {
        $validated = $request->validated();
        $reason->update($validated);
        return response([
            "message" => __("messages.support_reason_updated"),
            "support_reason" => $reason
        ]);
    }
    public function delete(SupportReason $reason) {
        $reason->delete();
        return response(["message" => __("messages.support_reason_deleted")]);
    }
    public function getAll(Request $request) {
        $support_reasons = SupportReason::orderByDesc("created_at")
            ->select(["id", "reason_en", "reason_ar"])
            ->get();
        return response(["support_reasons" => $support_reasons]);
    }
    public function getById(SupportReason $reason) {
        return response(["support_reason" => $reason]);
    }
}
