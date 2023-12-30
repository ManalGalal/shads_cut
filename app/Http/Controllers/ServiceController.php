<?php

namespace App\Http\Controllers;

use App\Http\Requests\createService;
use App\Http\Requests\updateService;
use App\Models\Service;


class ServiceController extends Controller {
    public function create(createService $request) {
        $validated = $request->validated();
        $service = Service::create($validated);
        return response(["message" => __("messages.service_created"), "service" => $service], 201);
    }
    public function update(updateService $request, Service $service) {
        $validated = $request->validated();
        $service->update($validated);
        return response(["message" => __("messages.service_updated"), "service" => $service]);
    }
    public function delete(Service $service) {
        $service->delete();
        return response(["message" => __("messages.service_deleted")]);
    }
    public function getById(Service $service) {
        return response(["service" => $service]);
    }
    public function getAll() {
        return response(["services" => Service::all()]);
    }
}
