<?php

namespace App\Http\Controllers;

use App\Http\Requests\createBrand;
use App\Http\Requests\updateBrand;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller {

    public function create(createBrand $request) {
        $validated = $request->validated();
        $brand = Brand::create($validated);
        return response(["message" => __("messages.brand_created"), "brand" => $brand], 201);
    }
    public function update(updateBrand $request, Brand $brand) {
        $validated = $request->validated();
        $brand->update($validated);
        return response(["message" => __("messages.brand_updated"), "brand" => $brand]);
    }
    public function delete(Brand $brand) {
        $brand->delete();
        return response(["message" => __("messages.brand_deleted")]);
    }
    public function getById(Request $request, Brand $brand) {
        // TODO: Refactor this request
        return response(["brand" => $brand]);
    }
    public function getAll(Request $request) {
        $brands = Brand::all();
        return response(["brands" => $brands]);
    }
}
