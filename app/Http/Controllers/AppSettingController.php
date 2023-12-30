<?php

namespace App\Http\Controllers;

use App\Http\Requests\createAppSetting;
use App\Http\Requests\updateAppSetting;
use App\Models\AppSetting;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppSettingController extends Controller {
    use HttpErrors;
    public function create(createAppSetting $request) {
        $validated = $request->validated();
        $app_setting = AppSetting::create($validated);
        return response(["message" => __("messages.app_setting_created"), "app_setting" => $app_setting], 201);
    }
    public function update(updateAppSetting $request, AppSetting $appSetting) {
        $validated = $request->validated();
        $appSetting->update($validated);
        return response(["message" => __("messages.app_setting_updated"), "app_setting" => $appSetting]);
    }
    public function delete(AppSetting $appSetting) {
        if ($appSetting->main) {
            return $this->FORBIDDEN(__("errors.app_settings_no_delete"));
        }
        $appSetting->delete();
        return response(["message" => __("messages.app_setting_deleted")]);
    }
    public function getByName(Request $request, AppSetting $appSetting) {
        if ($appSetting->private && !$request->user("api-admin")) {
            return $this->FORBIDDEN();
        }
        return response(["app_setting" => $appSetting]);
    }
    public function getAll(Request $request, AppSetting $app_setting) {
        $user = $request->user("api-admin");
        $whereQuery = $user ? " private = 1 or private = 0 " : " private = 0 ";
        $app_settings = AppSetting::whereRaw(DB::raw($whereQuery))
            ->paginate($request->number)
            ->withQueryString();
        return response(["app_settings" => $app_settings]);
    }
}
