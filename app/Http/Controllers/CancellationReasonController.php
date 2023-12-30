<?php

namespace App\Http\Controllers;

use App\Http\Requests\createCancellationReason;
use App\Http\Requests\updateCancellationReason;
use App\Models\CancellationReason;
use Illuminate\Http\Request;

class CancellationReasonController extends Controller {
    public function create(createCancellationReason $request) {
        $validated = $request->validated();
        $cancellationReason = CancellationReason::create($validated);
        return response([
            "message" => __("messages.cancellation_reason_created"),
            "cancellation_reason" => $cancellationReason
        ], 201);
    }
    public function update(updateCancellationReason $request, CancellationReason $cancellationReason) {
        $validated = $request->validated();
        $cancellationReason->update($validated);
        return response(["message" => __("messages.cancellation_reason_updated")]);
    }
    public function delete(CancellationReason $cancellationReason) {
        $cancellationReason->update();
        return response(["message" => __("messages.cancellation_reason_deleted")]);
    }
    public function getById(CancellationReason $cancellationReason) {
        return response(["cancellation_reason" => $cancellationReason]);
    }
    public function getAll() {
        return response(["cancellation_reasons" => CancellationReason::all()]);
    }
}
