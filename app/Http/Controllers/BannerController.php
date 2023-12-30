<?php

namespace App\Http\Controllers;

use App\Http\Requests\createBanner;
use App\Http\Requests\updateBanner;
use App\Models\Banner;
use App\Traits\DeleteFiles;
use Illuminate\Http\Request;

class BannerController extends Controller {
    use DeleteFiles;
    public function create(createBanner $request) {
        $validated = $request->validated();
        if ($request->hasFile("image")) {
            $validated["image"] = $request->file("image")->store("/banner/images");
        }
        $banner = Banner::create($validated);
        return response(["message" => __("messages.banner_created"), "banner" => $banner], 201);
    }
    public function update(updateBanner $request, Banner $banner) {
        $validated = $request->validated();
        if ($request->hasFile("image")) {
            $this->deleteFile($banner->image);
            $validated["image"] = $request->file("image")->store("/banner/images");
        }
        $banner->update($validated);
        return response(["message" => __("messages.banner_updated"), "banner" => $banner]);
    }
    public function delete(Banner $banner) {
        $banner->delete();
        return response(["message" => __("messages.banner_deleted")]);
    }
    public function getById(Banner $banner) {
        return response(["banner" => $banner]);
    }
    public function getAll(Request $request) {
        $banners = Banner::where("featured", true)
            ->paginate($request->number )
            ->withQueryString();
        return response(["banners" => $banners]);
    }
    public function getFeatured(Request $request) {
        $banners = Banner::where("featured", true)
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["banners" =>  $banners]);
    }
}
