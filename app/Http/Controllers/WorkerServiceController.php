<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Worker;
use App\Models\WorkerService;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class WorkerServiceController extends Controller {
    use HttpErrors;
    public function assignToWorker(Worker $worker, Service $service) {
        $woker_service_found = WorkerService::where("worker_id", $worker->id)
            ->where("service_id", $service->id)
            ->exists();
        if ($woker_service_found) {
            return $this->BAD_REQUEST(__("errors.worker_has_service"));
        }
        WorkerService::create(["worker_id" => $worker->id, "service_id" => $service->id]);
        return response(["message" => __("messages.service_added_to_worker")]);
    }
    public function removeFromWorker(Worker $worker, Service $service) {
        $woker_service_found = WorkerService::where("worker_id", $worker->id)
            ->where("service_id", $service->id)
            ->first();
        if (!$woker_service_found) {
            return $this->BAD_REQUEST(__("errors.worker_service_not_found"));
        }
        $woker_service_found->delete();
        return response(["message" => __("messages.service_removed_from_worker")]);
    }
}
